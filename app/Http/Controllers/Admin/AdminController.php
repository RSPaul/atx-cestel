<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use DB;
use Mail;
use App\User;
use App\Bookings;
use App\PaymentDetails;
use App\PaymentRequests;

use App\Mail\UserVerified;
use App\Mail\UserRevoked;
use App\Mail\PaymentAcceptAdmin;
use App\Mail\PaymentAcceptLaundress;

class AdminController extends Controller
{
    public function __construct() {
        $this->middleware(['auth','verified', 'admin']);
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function dashboard(Request $request) {
    	$profile = User::where(['id' => Auth::user()->id])->first();
    	return view('admin.dashboard')->with([ "profile" => $profile]);
    }

    public function users(Request $request) {
    	$type = $request->type;
    	$users = User::where(['user_type' => $type])
    					->get();
    	return view('admin.users')->with([ "users" => $users, 'type' => $type]);
    }

    public function verifyUser(Request $request) {
        try {
            $status = (isset($request->status)) ? $request->status : 1;
            User::where(['id' => $request->id])
                        ->update([
                            'status' => $status
                        ]);
            $response = array('success' => true,
                              'message' => 'User verified.');
            /*
            * TODO: SEND EMAILS
            */
            $user = User::where(['id' => $request->id])->first();
            if($status == 1) {
                if(!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'localhost'))){
                    Mail::to($user->email)
                        ->send(new UserVerified($user));
                }
            } else {
                if(!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'localhost'))){
                    Mail::to($user->email)
                        ->send(new UserRevoked($user));
                }
            }
        }catch(Exception $e) {
            $response = array('success' => false,
                              'message' => $e->getMessage());
        }
        return response()->json($response);
    }

    public function bookings(Request $request){
        $type = $request->type;
        $status = array('new');
        if($type == 'all') {
            $status = ['new', 'completed', 'declined', 'paid', 'canceled'];
        } else if($type == 'completed') {
            $status = ['completed'];
        } else if($type == 'paid') {
            $status = ['paid'];
        } else if($type == 'declined') {
            $status = ['declined'];
        }
        $bookings = Bookings::whereIn('status', $status)->get();
        return view('admin.bookings')->with([ "bookings" => $bookings, 'type' => $type]);

    }

    public function bookingDetails(Request $request){

        $id = $request->id;
        $booking = Bookings::where(['bookings.id' => $id])
                    ->join('users as customer', 'customer.id', '=', 'bookings.user_id')
                    ->join('users as provider', 'provider.id', '=', 'bookings.service_laundress')
                    ->select(DB::raw('bookings.*, customer.first_name as cfn, customer.last_name cln, customer.address ca, customer.city_state cs, customer.email ce, customer.phone cp, provider.first_name as pfn, provider.last_name pln, provider.address pa, provider.city_state ps, provider.email pe, provider.phone pp'))
                    ->first();
        // echo "<pre>"; print_r($booking); die();
        return view('admin.bookings_details')->with(['booking' => $booking]);

    }

    public function payments(Request $request){
        $type = $request->type;
        $status = array('requested');
        if($type == 'all') {
            $status = ['requested', 'paid'];
        } else if($type == 'new') {
            $status = ['requested'];
        } else if($type == 'paid') {
            $status = ['paid'];
        }

        $payments = DB::table('payment_requests')
                ->join('users', 'payment_requests.laundress_id', '=', 'users.id')
                ->whereIn('payment_requests.status', $status)
                ->select('payment_requests.amount', 'payment_requests.status', 'payment_requests.id', 'users.first_name', 'users.last_name', 'users.email', 'users.phone')
                ->get();
        return view('admin.payments')->with([ "payments" => $payments]);

    }

    public function viewPayment(Request $request) {
        $id = (int) $request->id;
        $reqs = PaymentRequests::where(['id' => $id])->first();
        $bookings = array();
        if($reqs) {
            $bookings = DB::table('bookings')
                ->where('bookings.service_laundress', $reqs->laundress_id)
                ->whereIn('bookings.id', unserialize($reqs->booking_ids))
                ->select('bookings.*')
                ->get();
        }
        return view('admin.view-payment')->with([ "reqs" => $reqs, "bookings" => $bookings]);
    }

    public function confirmPayment(Request $request) {
        $id = (int) $request->id;
        $pays = PaymentRequests::where(["id" => $id, "status" => "requested"])->first();
        if( $pays ) {

            $error = "";
            $pay_details = PaymentDetails::where(['user_id' => $pays->laundress_id])->first();
            if($pay_details) {
               
                $price = $pays->amount;
                $account = $pay_details->account;
                try {

                     $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $rn = substr(str_shuffle(str_repeat($pool, 5)), 0, 10);
                    $transfer_group = 'ORDER-'.$pays->id . '-' . $pays->laundress_id . '-'.$rn;

                    $laundress = User::find($pays->laundress_id);
                    $charge =  \Stripe\Transfer::create([
                      "amount" => $price * 100,
                      "currency" => "usd",
                      "destination" => $account,
                      "transfer_group" => $transfer_group
                    ]);

                    PaymentRequests::where(['id' => $id])
                                    ->update(["status" => 'paid']);

                    Bookings::whereIn('id', unserialize( $pays->booking_ids ))
                                    ->update(["payment_request" => '2']);

                    /*
                    * TODO: SEND MAIL TO ADMIN AND LAUNDRESS
                    */
                    if(!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'localhost'))){
                        Mail::to($chef->email)->send(new PaymentAcceptLaundress($chef));
                        Mail::to(env('ADMIN_EMAIL'))->send(new PaymentAcceptAdmin($chef));
                    }
                    
                    return response()->json(['response' => "Paid successfully!", 'success' => true]);

                } catch (\Stripe\Error\RateLimit $e) {
                    $error = $e->getMessage();                  
                } catch (\Stripe\Error\InvalidRequest $e) {
                    $error = $e->getMessage();
                } catch (\Stripe\Error\Authentication $e) {
                    $error = $e->getMessage();
                } catch (\Stripe\Error\ApiConnection $e) {
                    $error = $e->getMessage();
                } catch (\Stripe\Error\Base $e) {
                    $error = $e->getMessage();
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }

                return response()->json(['response' => $error , 'success' => false]);


            } else{
                return response()->json(['response' => "Payment details not found!", 'success' => false]);
            }
        }else{
            return response()->json(['response' => "No Request found!", 'success' => false]);
        }        
    }

    public function userDetails(Request $request) {
        $id = $request->id;
        $user = User::where(['id' => $id])->first();
        return view('admin.view-user')->with([ "user" => $user]);
    }
}
