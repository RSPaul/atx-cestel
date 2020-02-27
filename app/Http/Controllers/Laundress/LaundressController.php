<?php

namespace App\Http\Controllers\Laundress;

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
use App\PaymentDetails;
use App\Bookings;
use Carbon\Carbon;

class LaundressController extends Controller
{	
	public function __construct() {
        $this->middleware(['auth','verified', 'laundress'],  ['except' => ['get']]);
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function profile(Request $request) {
    	$profile = User::where(['id' => Auth::user()->id])->first();
    	return view('laundress.dashboard')->with([ "profile" => $profile]);
    }

    public function get($id) {
    	if($id == 'me') {
    		if (Auth::check()) {
    			$user = User::where(['id' => Auth::user()->id])->first();
    		} else {
    			$user = array();
    		}
    	} else {
    		$user = User::where(['id' => $id])->first();
    	}
    	$response = array(
                'status'  => 'success',
                'data'  => $user,
                'loggedIn' => (!empty($user)) ? true : false
            );
    	return response()->json($response);
    }

    public function uploadPicture(Request $request) {

        if(Auth::user()) {
            $data = $request->image;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $image_name = time().'.png';
            $path = public_path() . "/uploads/profiles/" . $image_name;

            file_put_contents($path, $data);

            User::where(['id' => Auth::user()->id])
                        ->update([
                            'profile_pic' => $image_name
                        ]);

        }

        return response()->json(['success'=>'done']);
    }

    public function dashboard(Request $request) {
        $tab_id = $request->tab;
        $profile = User::where(['id' => Auth::user()->id])->first();
        return view('laundress.dashboard')->with([ "profile" => $profile, "tab_id" => $tab_id]);
    }

    public function schedule(Request $request) {
        $next_week_bookings = array();
        $all_bookings = array();

        $bookings = Bookings::where(['service_laundress' => Auth::user()->id])
                    ->where(['bookings.status' => 'new'])
                    ->join('users', 'users.id', '=', 'bookings.user_id')
                    ->select(DB::raw('bookings.*, users.first_name, users.last_name, users.address, users.city_state'))
                    ->where('bookings.service_day', '=', date('m/d/Y'))
                    ->get();

        $next_week_bookings = Bookings::where(['service_laundress' => Auth::user()->id])
                    ->where(['bookings.status' => 'new'])
                    ->join('users', 'users.id', '=', 'bookings.user_id')
                    ->select(DB::raw('bookings.*, users.first_name, users.last_name, users.address, users.city_state'))
                    ->where('bookings.service_day', '>', date('m/d/Y'))
                    ->get();

        $all_bookings = Bookings::where(['service_laundress' => Auth::user()->id])
                    ->where(['bookings.status' => 'new'])
                    ->join('users', 'users.id', '=', 'bookings.user_id')
                    ->select(DB::raw('bookings.*, users.first_name, users.last_name, users.address, users.city_state'))
                    ->get();

        $past_bookings = Bookings::where(['service_laundress' => Auth::user()->id])
                    ->where('bookings.service_day', '<', date('m/d/Y'))
                    ->join('users', 'users.id', '=', 'bookings.user_id')
                    ->select(DB::raw('bookings.*, users.first_name, users.last_name, users.address, users.city_state'))
                    ->get();

        return response()->json(['bookings'=> array('today' => $bookings, 'next_week' => $next_week_bookings, 'all_bookings' => $all_bookings, 'past_bookings' => $past_bookings)]);
    }

    public function viewscheduleList(Request $request) {
        $allBooking = array();
        $today_bookings = array();
        $tom_bookings = array();
        $week_bookings = array();
        $month_bookings = array();

        $currentdate = date('m/d/Y');
        $tomDate = date( "m/d/Y", strtotime( "$currentdate +1 day" ) );
        $thisWeekDate = date( "m/d/Y", strtotime( "$currentdate +7 day" ) ); 
        $lastDayofWeek = Carbon::now()->endOfWeek();
        $lastDayofWeek = date( "m/d/Y", strtotime( $lastDayofWeek ) );
        $lastDay = date('t',strtotime('today'));
        $lastmonthdate = date('m/'.$lastDay.'/Y');

        $allBooking = Bookings::where(['service_laundress' => Auth::user()->id])
                    ->where(['bookings.status' => 'new'])
                    ->join('users', 'users.id', '=', 'bookings.user_id')
                    ->select(DB::raw('bookings.*, users.first_name, users.last_name, users.address, users.city_state'))
                    ->where('bookings.service_day', '>=',  $currentdate)
                    ->get();

        foreach ($allBooking as $key => $value) {

            if($value->service_day ==  $currentdate) {
                array_push($today_bookings, $value);
                array_push($week_bookings, $value);
                array_push($month_bookings, $value);
            }else if($value->service_day ==  $tomDate ) {
                array_push($tom_bookings, $value);
                array_push($week_bookings, $value);
                array_push($month_bookings, $value);
            }else if($value->service_day > date('m/d/Y') && $value->service_day <=  $lastDayofWeek){
                array_push($week_bookings, $value);
                array_push($month_bookings, $value);
            }else if($value->service_day > date('m/d/Y') && $value->service_day <=  $lastmonthdate){
                array_push($month_bookings, $value);
            }else{

            }      
        }            
       
        return response()->json(['bookings'=> array('today_bookings' => $today_bookings, 'tom_bookings' => $tom_bookings, 'week_bookings' => $week_bookings, 'month_bookings' => $month_bookings)]);
    }

    public function earningsByWeek(Request $request) {
        $now = Carbon::now();
        $now->startOfWeek(Carbon::MONDAY);
        $weekStartDate = $now->startOfWeek()->format('d/m/Y');
        $weekEndDate = $now->endOfWeek()->format('d/m/Y');

        $bookings = Bookings::where(['service_laundress' => Auth::user()->id])
                        ->whereBetween('service_day', [$weekStartDate, $weekEndDate])
                            ->get();
        $washing = array();
        $iorning = array();
        $bedMaking = array();
        $organizing = array();
        $packing = array();
        
        //get earnings by service type
        foreach($bookings as $booking) {
            $categories = unserialize($booking->service_categories);
            if(in_array('Washing', $categories)) {
                array_push($washing, $booking->service_amount);
            }
            if(in_array('Ironing', $categories)) {
                array_push($iorning, $booking->service_amount);
            }
            if(in_array('BedMaking', $categories)) {
                array_push($bedMaking, $booking->service_amount);
            }
            if(in_array('Organizing', $categories)) {
                array_push($organizing, $booking->service_amount);
            }
            if(in_array('Packing', $categories)) {
                array_push($packing, $booking->service_amount);
            }
        }
        $weekEarnings = array(
                            array('name'=> 'Washing', 'amount' => array_sum($washing)),
                            array('name'=> 'Ironing', 'amount' => array_sum($iorning)),
                            array('name'=> 'Bed Making', 'amount' => array_sum($bedMaking)),
                            array('name'=> 'Organizing', 'amount' => array_sum($organizing)),
                            array('name'=> 'Packing', 'amount' => array_sum($packing))
                        );
        $totalEarning = array_sum($washing) + array_sum($iorning) + array_sum($bedMaking) + array_sum($packing);
        return response()->json(['weekEarnings' => $weekEarnings, 'totalEarning' => $totalEarning, 'weekStart' => $now->startOfWeek()->format('m/d'), 'weekEnd' => $now->endOfWeek()->format('m/d')]);
    }

    public function declineBooking(Request $request) {
        $id = (int) $request->id;
        $booking = DB::table('bookings')
            ->where('bookings.id', $id)
            ->where('bookings.service_laundress', Auth::user()->id)
            ->first();

        $data = array();
        if($booking) {
            $data["status"] = 'declined';
            Bookings::where(['id' => $booking->id ])
                    ->update($data);
        } else {
            return response()->json(['response' => "Booking not found!", "status" => false]);
        }
    }

    public function updateAccount(Request $request) {

        $msg = "";
        $data = $request->all();
        $details = PaymentDetails::where(['user_id' => Auth::user()->id])->first();
        // echo "<pre>";
        // print_r($data);
        // die();
        if($details) {
            
            if($details->account_number != $data['account_number']) {
                $validatedData = $request->validate([
                    'account_number' => 'required|string|unique:payment_details',
                ]);
            }

            try {
                $account = \Stripe\Account::update(
                  $details->account,
                  [
                    'external_account' => [
                        'object' => 'bank_account',
                        'country' => 'US',
                        'currency' => 'usd',
                        'routing_number' => $data['routing_number'],
                        'account_number' => $data['account_number'],
                        'account_holder_name' => Auth::user()->name,
                    ],
                  ]
                );
                $data['account_id'] = $account->external_accounts->data[0]->id;
                $data['account'] = $account->external_accounts->data[0]->account;
                $data['bank_name'] = $account->external_accounts->data[0]->bank_name;
                $data['last4'] = $account->external_accounts->data[0]->last4;
                
                $details = PaymentDetails::find($details->id);
                $details->account_id = $account->external_accounts->data[0]->id;
                $details->account = $account->external_accounts->data[0]->account;
                $details->bank_name = $account->external_accounts->data[0]->bank_name;
                $details->last4 = $account->external_accounts->data[0]->last4;

                $details->save();

                return response()->json(['message' => "Payment Information updated successfully!", "status" => true]);

            } catch (\Stripe\Error\RateLimit $e) {
                $msg = $e->getMessage();              
            } catch (\Stripe\Error\InvalidRequest $e) {
                $msg = $e->getMessage();
            } catch (\Stripe\Error\Authentication $e) {
                $msg = $e->getMessage();
            } catch (\Stripe\Error\ApiConnection $e) {
                $msg = $e->getMessage();
            } catch (\Stripe\Error\Base $e) {
                $msg = $e->getMessage();
            } catch (Exception $e) {
                $msg = $e->getMessage();
            }
            
            return response()->json(['message' => $msg, "status" => false]);
        
        } else {

            $validatedData = $request->validate([
                'account_number' => 'required|string|unique:payment_details',
            ]);

            try {

                $account = \Stripe\Account::create([
                    'country' => 'US',
                    'type' => 'custom',
                    'requested_capabilities' => ["transfers"],
                    'external_account'=> [
                        'object' => 'bank_account',
                        'country' => 'US',
                        'currency' => 'usd',
                        'routing_number' => $data['routing_number'],
                        'account_number' => $data['account_number'],
                        'account_holder_name' => Auth::user()->name,
                    ]
                ]);

                $data['user_id'] = Auth::user()->id;
                $data['account_type'] = 'custom';
                $data['account_id'] = $account->external_accounts->data[0]->id;
                $data['account'] = $account->external_accounts->data[0]->account;
                $data['bank_name'] = $account->external_accounts->data[0]->bank_name;
                $data['last4'] = $account->external_accounts->data[0]->last4;

                $details = new PaymentDetails($data);
                $details->save();
                
                return response()->json(['message' => 'Payment Information saved successfully!', "status" => true]);

            } catch (\Stripe\Error\RateLimit $e) {
                $msg = $e->getMessage();              
            } catch (\Stripe\Error\InvalidRequest $e) {
                $msg = $e->getMessage();
            } catch (\Stripe\Error\Authentication $e) {
                $msg = $e->getMessage();
            } catch (\Stripe\Error\ApiConnection $e) {
                $msg = $e->getMessage();
            } catch (\Stripe\Error\Base $e) {
                $msg = $e->getMessage();
            } catch (Exception $e) {
                $msg = $e->getMessage();
            }

            return response()->json(['message' => $msg, "status" => false]);
        }
    }

    public function getAccount(Request $request) {
        $details = PaymentDetails::where(['user_id' => Auth::user()->id])->first();
        $bookings = DB::table('bookings')
            ->where('bookings.service_laundress', Auth::user()->id)
            ->whereIn('bookings.status', ['completed'])
            ->join('users', 'users.id', '=', 'bookings.user_id')
                    ->select(DB::raw('bookings.*, users.first_name, users.last_name, users.address, users.city_state'))
            ->orderBy('bookings.id', 'desc')
            ->get();
        return response()->json(['message' => $details, "status" => true, 'bookings' => $bookings]);
    }

}
