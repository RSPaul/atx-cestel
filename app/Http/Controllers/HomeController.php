<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use DB;
use Mail;
use App\User;
use App\UserCards;
use App\Bookings;
use App\UserPayments;
use App\Mail\VerificationEmail;
use App\Mail\BookingCreate;
use App\Mail\BookingCreateUser;

class HomeController extends Controller {
    
    public function __construct() {
        Stripe::setApiKey(env('STRIPE_SECRET'));        
    }

    public function register(Request $request) {

    	$data = $request->all();

    	// $request->validate([
     //        'first_name' => ['required', 'string', 'max:255'],
     //        'last_name' => ['required', 'string', 'max:255'],
     //        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
     //        'password' => ['required', 'string', 'min:8'],
     //    ]);
    	
    	$data['services'] = (isset($data['services'])) ? serialize($data['services']) : '';
    	$data['password'] = Hash::make($data['password']);
    	$data['status'] = 0;
        $data['user_type'] = 'user';
    	try {
            /*
            * Check If Email Exists
            */
            $email = User::where(['email' => $data['email']])->first();
            if($email && $email->id) {
                return redirect('/register')->with('error', 'Email already exists, please use another email address.');
            }
        	$user = User::create($data);
            $remember_token = $this->generate_token();
            User::where(['id' => $user->id])
                        ->update([
                            'remember_token' => $remember_token
                        ]);
            $link = $this->get_server_url() . '/verify/email/' .$remember_token;

            //save card, if card details are not empty
            if($data['card_name'] !='' && $data['card_number'] !='' && $data['expiry_month'] !='' && $data['expiry_year'] !='' && $data['security_code'] !='' && $data['zip_card'] !='' && $data['b_address'] !='' && $data['b_city_state'] !='') {
                $data['user_id'] = $user->id;
                $data['zip'] = $data['zip_card'];
                UserCards::create($data);
            }

            if(!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'localhost'))){
                Mail::to([$user->email])->send(new VerificationEmail($user, $link));
            }
            return redirect('/verify')->with('success', 'Registered successfully.');
        } catch (\Illuminate\Database\QueryException $exception) {

            return redirect('/register')->with('error',$exception->getMessage());
        }
    }

    public function generate_token() {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($permitted_chars), 0, 16);
    }

    public function get_server_url() {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'];
    }

    public function book(Request $request) {
        if($request->isMethod('post')) {
            $service_type = $request->service_type;
            $service_address = $request->service_address;
            $service_categories = $request->service_categories;
            $service_beds = $request->service_beds; 
            $service_day = $request->service_day;             
            $service_time = $request->service_time; 
            $service_laundress = $request->service_laundress; 
            $service_package = $request->service_package; 
            $service_job_details = $request->service_job_details; 
            $service_folding_details = $request->service_folding_details; 
            $service_hanging_details = $request->service_hanging_details; 
            $service_washing_details = $request->service_washing_details; 
            $service_amount = $request->service_amount; 
            $service_quantity = $request->service_quantity; 
            $service_description = $request->service_description; 
            $service_payment_type = $request->service_payment_type; 
            $user_name = $request->user_name; 
            $user_email = $request->user_email; 
            $user_address = $request->user_address; 
            $user_city = $request->user_city; 
            $user_state = $request->user_state; 
            $user_zip = $request->user_zip; 
            $user_country = $request->user_country; 
            $card_name = $request->card_name; 
            $card_number = $request->card_number; 
            $card_expiry_month = $request->card_expiry_month; 
            $card_expiry_year = $request->card_expiry_year; 
            $card_security_code = $request->card_security_code; 

            $request->session()->put('booking[service_type]', $request->service_type);
            $request->session()->put('booking[service_address]', $request->service_address);
            $request->session()->put('booking[service_categories]', $request->service_categories);
            $request->session()->put('booking[service_beds]', $request->service_beds); 
            $request->session()->put('booking[service_day]', $request->service_day);             
            $request->session()->put('booking[service_time]', $request->service_time); 
            $request->session()->put('booking[service_laundress]', $request->service_laundress); 
            $request->session()->put('booking[service_package]', $request->service_package); 
            $request->session()->put('booking[service_job_details]', $request->service_job_details); 
            $request->session()->put('booking[service_folding_details]', $request->service_folding_details); 
            $request->session()->put('booking[service_hanging_details]', $request->service_hanging_details); 
            $request->session()->put('booking[service_washing_details]', $request->service_washing_details); 
            $request->session()->put('booking[service_amount]', $request->service_amount);
            $request->session()->put('booking[service_quantity]', $request->service_quantity);
            $request->session()->put('booking[service_description]', $request->service_description); 
            $request->session()->put('booking[service_payment_type]', $request->service_payment_type); 
            $request->session()->put('booking[user_name]', $request->user_name); 
            $request->session()->put('booking[user_email]', $request->user_email); 
            $request->session()->put('booking[user_address]', $request->user_address); 
            $request->session()->put('booking[user_city]', $request->user_city); 
            $request->session()->put('booking[user_state]', $request->user_state); 
            $request->session()->put('booking[user_zip]', $request->user_zip); 
            $request->session()->put('booking[user_country]', $request->user_country); 
            $request->session()->put('booking[card_name]', $request->card_name); 
            $request->session()->put('booking[card_number]', $request->card_number); 
            $request->session()->put('booking[card_expiry_month]', $request->card_expiry_month); 
            $request->session()->put('booking[card_expiry_year]', $request->card_expiry_year); 
            $request->session()->put('booking[card_security_code]', $request->card_security_code); 
        } else {
            $service_type = $request->session()->get('booking[service_type]');
            $service_address = $request->session()->get('booking[service_address]');
            $service_categories = $request->session()->get('booking[service_categories]');
            $service_beds = $request->session()->get('booking[service_beds]'); 
            $service_day = $request->session()->get('booking[service_day]');             
            $service_time = $request->session()->get('booking[service_time]'); 
            $service_laundress = $request->session()->get('booking[service_laundress]'); 
            $service_package = $request->session()->get('booking[service_package]'); 
            $service_job_details = $request->session()->get('booking[service_job_details]'); 
            $service_folding_details = $request->session()->get('booking[service_folding_details]'); 
            $service_hanging_details = $request->session()->get('booking[service_hanging_details]'); 
            $service_washing_details = $request->session()->get('booking[service_washing_details]'); 
            $service_amount = $request->session()->get('booking[service_amount]');
            $service_quantity = $request->session()->get('booking[service_quantity]'); 
            $service_description = $request->session()->get('booking[service_description]'); 
            $service_payment_type = $request->session()->get('booking[service_payment_type]'); 
            $user_name = $request->session()->get('booking[user_name]'); 
            $user_email = $request->session()->get('booking[user_email]'); 
            $user_address = $request->session()->get('booking[user_address]'); 
            $user_city = $request->session()->get('booking[user_city]'); 
            $user_state = $request->session()->get('booking[user_state]'); 
            $user_zip = $request->session()->get('booking[user_zip]'); 
            $user_country = $request->session()->get('booking[user_country]'); 
            $card_name = $request->session()->get('booking[card_name]'); 
            $card_number = $request->session()->get('booking[card_number]'); 
            $card_expiry_month = $request->session()->get('booking[card_expiry_month]'); 
            $card_expiry_year = $request->session()->get('booking[card_expiry_year]'); 
            $card_security_code = $request->session()->get('booking[card_security_code]'); 
        }
        
        $price = 0;
        $profile = (object) array('id'=> '', 'first_name' => '', 'last_name' => '', 'address' => '', 'city_state' => '', 'zip' => '', 'phone' => '', 'email' => '');
        if(isset($service_categories) && sizeof($service_categories)) {
            foreach($service_categories as $service) {
                $service_price = env(strtoupper($service) . '_PRICE');
                $price = $price + $service_price;
            }
        }
        $total_price = $price * $service_quantity;
        if (Auth::check()) {
            $profile = User::where(['id' => Auth::user()->id])->first(); 
        }
        $laundress = User::where(['user_type' => 'laundress'])->get(); 
        

        // get laundress email
        $laundress_data = User::where(['id' => $service_laundress])->first(); 

        if($request->isMethod('post')) {

            $success = true;
            $return_url = '/user/dashboard';
            try {
                //if user is not logged in create new user
                if(!Auth::check()) {
                    $user_data = $request->get('register');
                    $user_data['password'] = Hash::make($user_data['password']);
                    $user_data['address'] = $user_address;
                    $user_data['zip'] = $user_zip;
                    $user_data['status'] = 1;
                    $user_data['user_type'] = 'user';
                    $profile = User::create($user_data);
                    Auth::login($profile);
                    $return_url = '/thank-you';
                }
                //create stripe payment
                $token = \Stripe\Token::create([
                    "card" => array(
                        'name' => $request->get('card_name'),
                        "number" => $request->get('card_number'),
                        "exp_month" => $request->get('card_expiry_month'),
                        "exp_year" => $request->get('card_expiry_year'),
                        "cvc" => $request->get('card_security_code')
                    ),
                ]);

                
                //create customer if not created
                if($profile->customer_id) {
                    $customer_id = $profile->customer_id;
                } else {
                    $customer = \Stripe\Customer::create(
                        [
                            'source' => $token['id'],
                            'email' =>  $user_email,
                            'description' => 'My name is '. $profile->first_name,
                        ]
                    );
                    $customer_id = $customer['id'];  
                    User::where(['id' => $profile->id ])
                        ->update([
                            "customer_id" => $customer_id]);                  
                }

                $data = $request->all();
                $data['user_id'] = $profile->id;
                $data['status'] = 'new';
                $data['service_categories'] = serialize($data['service_categories']);
                $booking = Bookings::create($data);
                $request->session()->put('booking', null);
                $message = 'Booking Successful.';

                $data['first_name'] = $profile->first_name;
                $data['last_name'] = $profile->last_name;
                $data['adress'] = $profile->adress;

                /*
                * Transfer the amount to admin account
                */
                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $rn = substr(str_shuffle(str_repeat($pool, 5)), 0, 10);
                $transfer_group = 'ORDER-'.$booking->id . '-' . $profile->id . '-'.$rn;
                Bookings::where(['id'=> $booking->id])
                            ->update(['transfer_group' => $transfer_group]);

                /*
                * Save to payment history
                */
                UserPayments::create([
                        'user_id' => $profile->id,
                        'booking_id' => $booking->id
                ]);
                //email to Laundress
                if(!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'localhost'))){
                    // $Toemail = 'parthibatman@gmail.com';
                    Mail::to([$laundress_data->email])->send(new BookingCreate($data, $laundress_data));

                    //email to customer
                    Mail::to([$profile->email])->send(new BookingCreateUser($data, $laundress_data));
                }


            } catch (\Stripe\Error\RateLimit $e) {
              $success = false;
              $message = $e->getMessage();
            } catch (\Stripe\Error\InvalidRequest $e) {
              $success = false;
              $message = $e->getMessage();
            } catch (\Stripe\Error\Authentication $e) {
              $success = false;
              $message = $e->getMessage();
            } catch (\Stripe\Error\ApiConnection $e) {
              $success = false;
              $message = $e->getMessage();
            } catch (\Stripe\Error\Base $e) {
              $success = false;
              $message = $e->getMessage();
            } catch (Exception $e) {
              $success = false;
              $message = $e->getMessage();
            }
            $response = array('success' => $success,
                              'message' => $message,
                              'return_url' => $return_url);
            return response()->json($response);
        } else {
            return view('book')->with([ "profile" => $profile, "price" => $price, "total_price" => $total_price, 'laundress' => $laundress]);
        }
    }

    public function booking_checkout(Request $request) {
        $user_id = Auth::user()->id;
        $data = $request->all();
        $data['user_id'] = $user_id;

        Bookings::create($data);

        $msg = array(
            'status'  => 'success',
            'message' => 'Booking Successful'
        );
        return response()->json($msg);
    }

    public function bePartTeam(Request $request){
        //if logged in as user redirect to profile page
        if(Auth::check()) {
            return redirect('/profile');
        }
        if($request->isMethod('post')) {

            $data = $request->all();
            $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', 'min:8'],
            ]);
            
            $data['language']       = (isset($data['language'])) ? serialize($data['language']) : '';
            $data['available']      = (isset($data['available'])) ? serialize($data['available']) : '';
            $data['more_questions'] = (isset($data['more_questions'])) ? serialize($data['more_questions']) : '';
            $data['password'] = Hash::make($data['password']);
            $data['status'] = 0;
            $data['user_type'] = 'laundress';

            try {
                /*
                * Check If Email Exists
                */
                $email = User::where(['email' => $data['email']])->first();
                if($email && $email->id) {
                    return redirect('/be-part-team')->with('error', 'Email already exists, please use another email address.');
                }
                $user = User::create($data);

                /*
                * TODO: SEND MAIL TO ADMIN AND USER
                */
                return redirect('/be-part-team')->with('success', 'Account created successfully. Once Admin approves you will notified via email');

            } catch (\Illuminate\Database\QueryException $exception) {

                $errorInfo = $exception->errorInfo;
                return redirect('/be-part-team')->with('error', $exception->getMessage());
            }
        }else{
            return view('be-part-team');
        }

    }
}
