<?php

namespace App\Http\Controllers\User;

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
use Carbon\Carbon;
use App\Mail\BookingCanceledUser;
use App\Mail\BookingCanceledLaundress;
use App\Mail\BookingCompleteLaundress;
use App\Mail\BookingCompleteUser;

class UserController extends Controller
{	
	public function __construct() {
        $this->middleware(['auth','verified', 'user'],  ['except' => ['get', 'updateProile']]);
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function profile(Request $request) {
    	$profile = User::where(['id' => Auth::user()->id])->first();
    	return view('user.dashboard')->with([ "profile" => $profile]);
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
        if(Auth::check()) {
            $msg = '';
            $status = true;
            try {
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
            } catch (Exception $e) {
                $status = false;
                $msg = $e->getMessage();
            }

        }

        return response()->json(['success'=> $status, 'msg' => $msg]);
    }

    public function dashboard(Request $request) {
        $tab_id = $request->tab;
        $profile = User::where(['id' => Auth::user()->id])->first();
        return view('user.dashboard')->with([ "profile" => $profile, "tab_id" => $tab_id]);
    }

    public function updateProile(Request $request) {
        $profile = $request->all();
        $message = 'Profile updated.';
        $success = true;
        try {
            if($profile['password'] != '' && Hash::make($profile['current_password']) == Auth::user()->password) {
                User::where(['id' => Auth::user()->id])
                        ->update([
                            'password' => Hash::make($profile['password'])
                        ]);
                User::where(['id' => Auth::user()->id])
                            ->update($profile);
            } else if($profile['password'] == '' || @$profile['password']) {
                unset($profile['password']);
                unset($profile['current_password']);
                unset($profile['confirm_password']);
                User::where(['id' => Auth::user()->id])
                            ->update($profile);
               
            } else {
                $success = false;
                $message = 'Your current password is invalid.';
            }
        } catch(Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }
        $response = array('success' => $success,
                          'message' => $message,
                          'data' => $profile);
        return response()->json($response);
    }

    public function schedule(Request $request) {
        $next_week_bookings = array();
        $all_bookings = array();

        $bookings = Bookings::where(['user_id' => Auth::user()->id])
                    ->where(['bookings.status' => 'new'])
                    ->join('users', 'users.id', '=', 'bookings.service_laundress')
                    ->select(DB::raw('bookings.*, users.first_name, users.last_name, users.address, users.city_state'))
                    ->where('bookings.service_day', '=', date('m/d/Y'))
                    ->get();

        $next_week_bookings = Bookings::where(['user_id' => Auth::user()->id])
                    ->where(['bookings.status' => 'new'])
                    ->join('users', 'users.id', '=', 'bookings.service_laundress')
                    ->select(DB::raw('bookings.*, users.first_name, users.last_name, users.address, users.city_state'))
                    ->where('bookings.service_day', '>', date('m/d/Y'))
                    ->get();

        $all_bookings = Bookings::where(['user_id' => Auth::user()->id])
                    ->where(['bookings.status' => 'new'])
                    ->join('users', 'users.id', '=', 'bookings.service_laundress')
                    ->select(DB::raw('bookings.*, users.first_name, users.last_name, users.address, users.city_state'))
                    ->get();

        $past_bookings = Bookings::where(['user_id' => Auth::user()->id])
                    ->where('bookings.service_day', '<', date('m/d/Y'))
                    ->orWhere('bookings.status', '=', 'completed')
                    ->join('users', 'users.id', '=', 'bookings.service_laundress')
                    ->select(DB::raw('bookings.*, users.first_name, users.last_name, users.address, users.city_state'))
                    ->get();
                    

        return response()->json(['bookings'=> array('today' => $bookings, 'next_week' => $next_week_bookings, 'all_bookings' => $all_bookings,'past_bookings' => $past_bookings)]);
    }

    public function viewscheduleListCustom(Request $request){
             $data = $request->all();
             $allBooking = array();
             //print_r($data);
             $to_date = date( "m/d/Y", strtotime( $data['to_date'] ) );
             $from_date = date( "m/d/Y", strtotime( $data['from_date'] ) );
             $allBooking = Bookings::where(['user_id' => Auth::user()->id])
                    ->where(['bookings.status' => 'new'])
                    ->join('users', 'users.id', '=', 'bookings.service_laundress')
                    ->select(DB::raw('bookings.*, users.first_name, users.last_name, users.address, users.city_state'))
                    ->where('bookings.service_day', '>=',  $from_date)
                    ->where('bookings.service_day', '<=',  $to_date)
                    ->get();
                  
            return response()->json(['customresult' => $allBooking ]);

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

        $allBooking = Bookings::where(['user_id' => Auth::user()->id])
                    ->where(['bookings.status' => 'new'])
                    ->join('users', 'users.id', '=', 'bookings.service_laundress')
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

    public function cancelBookingAmount(Request $request) {
        $id = (int) $request->id;
        $booking = DB::table('bookings')
            ->where('bookings.user_id', Auth::user()->id)
            ->where('bookings.id', $id)
            ->first();

        $data = array();
        if($booking) {

            $start = $booking->created_at;
            $scheduledate = $booking->service_day;
            //$scheduletime = $booking->booking_time;
            $combinedDT = date('Y-m-d H:i:s', strtotime("$scheduledate")); 
            $now   = date('Y-m-d H:i:s');
            $hoursAdded = date('Y-m-d H:i:s',strtotime('+3 hour',strtotime($start)));
            $hoursAddedten = date('Y-m-d H:i:s',strtotime('-10 hour',strtotime($combinedDT)));
            $hoursAddedthree = date('Y-m-d H:i:s',strtotime('-3 hour',strtotime($combinedDT)));

            $amount = 0;

            if(strtotime($hoursAdded) > strtotime($now)) {
             // echo "In ONe";
                $amount = 0;
            }else if(strtotime($now) > strtotime($hoursAddedthree) && strtotime($combinedDT) > strtotime($now) ){

              // To Deduct Full Amount as booking is cancelled less than 3 hours of schedule
                $amount = $booking->price;

            }else if(strtotime($hoursAddedthree) > strtotime($now)  && strtotime($combinedDT) > strtotime($now)){

                $amount = round($booking->price / 2, 2);
            } 
                
            return response()->json(['response' => "Booking is canceled!", "amount" => $amount]);           
        } else{
            return response()->json(['response' => "Booking not found!", "status" => false]);
        }
    }

    public function cancelBooking(Request $request) {
        $id = (int) $request->id;
        $booking = DB::table('bookings')
            ->where('bookings.id', $id)
            ->where('bookings.user_id', Auth::user()->id)
            ->first();

        $data = array();
        if($booking) {
            $data["status"] = 'canceled';
            Bookings::where(['id' => $booking->id ])
                    ->update($data);

            $start = $booking->created_at;
            $scheduledate = $booking->service_day;
            //$scheduletime = $booking->booking_time;
            $combinedDT = date('Y-m-d H:i:s', strtotime("$scheduledate")); 
            $now   = date('Y-m-d H:i:s');
            $hoursAdded = date('Y-m-d H:i:s',strtotime('+3 hour',strtotime($start)));
            $hoursAddedten = date('Y-m-d H:i:s',strtotime('-10 hour',strtotime($combinedDT)));
            $hoursAddedthree = date('Y-m-d H:i:s',strtotime('-3 hour',strtotime($combinedDT)));

            $amount = 0;

            if(strtotime($hoursAdded) > strtotime($now)) {
             // echo "In ONe";
                $amount = 0;
            }else if(strtotime($now) > strtotime($hoursAddedthree) && strtotime($combinedDT) > strtotime($now) ){

              // To Deduct Full Amount as booking is cancelled less than 3 hours of schedule
                $amount = $booking->price;

            }else if(strtotime($hoursAddedthree) > strtotime($now)  && strtotime($combinedDT) > strtotime($now)){

                $amount = round($booking->price / 2, 2);
            } 

            if($amount != 0) {
                try {
                    $charge = \Stripe\Charge::create([
                        'currency' => 'USD',
                        'customer' => $booking->customer,
                        'amount' =>  $amounttobecharged,
                        "transfer_group" => $booking->transfer_group,
                    ]);
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
            }
            /*
            * TODO: SEND EMAILS
            */
            $laundress = User::where(['id' => $booking->service_laundress])->first();
            if(!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'localhost'))){
                //TO Laundress
                Mail::to($laundress->email)
                    ->send(new BookingCanceledLaundress(Auth::user(), $laundress, $booking));
                //TO User
                Mail::to(Auth::user()->email)
                    ->send(new BookingCanceledUser(Auth::user(), $laundress, $booking));
            }

            return response()->json(['response' => "Booking is canceled!", "status" => true]); 
        } else{
            return response()->json(['response' => "Booking not found!", "status" => false]);
        }
    }

    public function completeBooking(Request $request) {
        $id = (int) $request->id;
        $booking = DB::table('bookings')
            ->where('bookings.id', $id)
            ->where('bookings.user_id', Auth::user()->id)
            ->first();
        if($booking) {
            $data = array();
            $data["status"] = 'completed';
            Bookings::where(['id' => $booking->id ])
                    ->update($data);
            try {
                $charge = \Stripe\Charge::create([
                    'currency' => 'USD',
                    'customer' => Auth::user()->customer_id,
                    'amount' =>  (int) $booking->service_amount * 2,
                    "transfer_group" => $booking->transfer_group,
                ]);
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
            /*
            * TODO: SEND EMAILS
            */
            if(!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'localhost'))){
                //TO Laundress
                Mail::to($laundress->email)
                    ->send(new BookingCompleteLaundress(Auth::user(), $laundress, $booking));
                //TO User
                Mail::to(Auth::user()->email)
                    ->send(new BookingCompleteUser(Auth::user(), $laundress, $booking));
            }
            return response()->json(['response' => "Booking is completed!", "status" => true]); 
        } else {
            return response()->json(['response' => "Booking not found!", "status" => false]);
        }
    }

}
