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
use Session;
use App\User;
use App\UserCards;
use App\Bookings;
use App\UserPayments;
use App\Mail\VerificationEmail;
use App\Mail\BookingCreate;
use App\Mail\BookingCreateUser;
use App\Mail\RecurringPaymentUser;
use App\Mail\BookingReminder;
use App\Mail\WelcomeEmail;

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

    public function getTimeSlots(Request $request) {
        $user = User::where(['id' => $request->id])->first();
        $time_slots = unserialize($user->available);
        $available_times = array();
        if(!empty($time_slots)) {            
            foreach ($time_slots as $key => $slot) {
                array_push($available_times, array('day' => strtoupper($key), 'slots' => array('from' => $slot['from'], 'to' => $slot['to'])));
            }
        }
        $response = array('success' => true,
                            'data' => $available_times);
        return response()->json($response);
    }

    public function book(Request $request) {
        // echo "<pre>";
        // print_r($request->session()->get('booking'));die();
        if($request->isMethod('post')) {

            $service_type = $request->service_type;
            $service_address = $request->service_address;
            $service_categories = $request->service_categories;
            // $service_beds = $request->service_beds; 
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

            $request->session()->put('booking[service_type]', $request->service_type);
            $request->session()->put('booking[service_address]', $request->service_address);
            $request->session()->put('booking[service_categories]', $request->service_categories);
            // $request->session()->put('booking[service_beds]', $request->service_beds); 
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
        } else {
            $service_type = $request->session()->get('booking[service_type]');
            $service_address = $request->session()->get('booking[service_address]');
            $service_categories = $request->session()->get('booking[service_categories]');
            // $service_beds = $request->session()->get('booking[service_beds]'); 
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
        }
        
        $price = 0;
        $profile = (object) array('id'=> '', 'first_name' => '', 'last_name' => '', 'address' => '', 'city_state' => '', 'zip' => '', 'phone' => '', 'email' => '');
        // if(isset($service_categories) && sizeof($service_categories)) {
        //     foreach($service_categories as $service) {
        //         $service_price = env(strtoupper($service) . '_PRICE');
        //         $price = $price + $service_price;
        //     }
        // }
        $total_price = ($service_amount !='') ? $service_amount : 0;//$price * $service_quantity;
        if (Auth::check()) {
            $profile = User::where(['id' => Auth::user()->id])->first(); 
        }
        $laundress = User::where(['user_type' => 'laundress'])->get(); 
        

        // get laundress email
        $laundress_data = User::where(['id' => $service_laundress])->first(); 
        
        if($request->isMethod('post')) {

            $success = true;
            try {
                //if user is not logged in create new user
                if(!Auth::check()) {
                    /*
                    * Check If Email Exist
                    */
                    $user_data = $request->get('register');
                    $email = User::where(['email' => $user_data['email']])->first();
                    if($email && $email->id) {
                        $response = array('success' => false,
                              'message' => 'Email address already exists, please use another email.');
                        return response()->json($response);
                        die();
                    }
                   
                    $user_data['password'] = Hash::make($user_data['password']);
                    $user_data['address'] = $user_address;
                    $user_data['zip'] = $user_zip;
                    $user_data['status'] = 1;
                    $user_data['user_type'] = 'user';
                    $profile = User::create($user_data);
                    Auth::login($profile);
                    $return_url = '/thank-you';

                    /*
                    * SEND WELCOME EMAIL
                    */
                    if(!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'localhost'))){
                        Mail::to([$user_data['email']])->send(new WelcomeEmail($user_data));
                    }
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
                if($data['service_payment_type'] == 'weekly'){
                    $data['next_payment_date'] = date( "m/d/Y", strtotime( "$service_day +7 day" ) );
                }else if($data['service_payment_type'] == 'bi-weekly'){
                    $data['next_payment_date'] = date( "m/d/Y", strtotime( "$service_day +14 day" ) );
                }else if ($data['service_payment_type'] == 'monthly'){
                    $data['next_payment_date'] = date( "m/d/Y", strtotime( "$service_day +1 month" ) );
                }else{
                    $data['next_payment_date'] = '';
                }
                $data['service_categories'] = serialize($data['service_categories']);
                $data['service_quantity'] = serialize($data['service_quantity']);
                $booking = Bookings::create($data);                
                /*
                * FLUSH SESSION DATA
                */
                $this->flushBookingData($request);
                $message = 'Booking Successful.';

                $data['first_name'] = $profile->first_name;
                $data['last_name'] = $profile->last_name;
                $data['adress'] = $profile->adress;

                $return_url = '/thank-you/' . $booking->id;

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
                if(!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'localhost'))){
                    Mail::to([$user_data['email'], env('ADMIN_EMAIL')])->send(new WelcomeEmail($user_data));
                }
                return redirect('/be-part-team')->with('success', 'Account created successfully. Once Admin approves you will notified via email');

            } catch (\Illuminate\Database\QueryException $exception) {

                $errorInfo = $exception->errorInfo;
                return redirect('/be-part-team')->with('error', $exception->getMessage());
            }
        }else{
            return view('be-part-team');
        }

    }

    public function thankYou(Request $request) {
        return view('thank-you');
    }

    public function serviceReminderEmails(Request $request) {
        $type = $request->type;
        $now = date('m/d/Y h:i:s');
        if($type == '1') {
            //get all bookings after 1 hour from current time
            // echo " send email to all laundress with booking in next 1 hour";
            $date = date('m/d/Y');
            $bookings = Bookings::where(['service_day' => $date, 
                                    'service_reminder_sent' => 0])
                        ->join('users', 'users.id', '=', 'bookings.service_laundress')
                        ->select(DB::raw('bookings.*, users.email,users.first_name,users.last_name'))
                        ->get();
            // echo "<pre>";
            // print_r($bookings);
            foreach ($bookings as $key => $booking) {
                //print_r($booking->service_time);
                //check if service time is 1 hours from now
                // echo "<br>";
                $date = $booking->service_day;
                $timeArray = explode('-',$booking->service_time);
                //print_r($time[0]);
                if(isset($timeArray[0])) {
                    // echo date('h:i', strtotime($time[0]));
                    $time = $timeArray[0];
                    $one_hour_past = date('Y-m-d h:i:s', time() - 3600);
                    // echo "<br>";
                    $service_date = date('Y-m-d h:i:s', strtotime("$date $time"));
                    if($one_hour_past >=  $service_date) {
                        /*
                        * SEND EMAIL
                        */
                        // echo "send email";
                        Bookings::where(['id' => $booking->id])
                                    ->update([
                                    "service_reminder_sent" => 1]);   
                        if(!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'localhost'))){
                            Mail::to([$user->email])->send(new BookingReminder($booking));
                        }   
                    }
                }
            }
            // echo "</pre>";
        } elseif ($type == '24') {
            //get all bookings after 24 hour from current time
            // echo " send email to all laundress with booking in next 24 hours";
            $date = date('m/d/Y');
            $bookings = Bookings::where(['service_day' => $date, 
                                    'service_reminder_sent' => 0])
                                    ->get();
            // echo "<pre>";
            // print_r($bookings);
            foreach ($bookings as $key => $booking) {
                //print_r($booking->service_time);
                //check if service time is 1 hours from now
                // echo "<br>";
                $date = $booking->service_day;
                $timeArray = explode('-',$booking->service_time);
                //print_r($time[0]);
                if(isset($timeArray[0])) {
                    // echo date('h:i', strtotime($time[0]));
                    $time = $timeArray[0];
                    $twenty_four_hour_past = date('Y-m-d h:i:s', time() - (3600 * 24));
                    // echo "<br>";
                    // echo $service_date = date('Y-m-d h:i:s', strtotime("$date $time"));
                    if($twenty_four_hour_past >=  $service_date) {
                        /*
                        * SEND EMAIL
                        */
                        // echo "send email";
                        if(!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'localhost'))){
                            Mail::to([$user->email])->send(new BookingReminder($value));
                        }
                    }
                }
            }
            // echo "</pre>";
        }
    }

   public function cronPayment(){
    
        $date = date('m/d/Y');   
        $bookings = Bookings::where(['bookings.status' => 'new', 'bookings.payment_request' => '1'])
                    ->join('users', 'users.id', '=', 'bookings.user_id')
                    ->select(DB::raw('bookings.id,bookings.service_amount,bookings.transfer_group,bookings.next_payment_date,bookings.service_payment_type, users.email,users.first_name,users.last_name, users.customer_id,bookings.user_id'))
                    ->where('bookings.service_payment_type', '!=', 'OneTime' )
                    ->get();

        foreach ($bookings as $key => $value) {
                        # code...
            if($date == $value['next_payment_date']){
                //echo "Yes";
               // echo "<pre>";print_r($value);
                $newdate = $value['next_payment_date'];
                if($value['service_payment_type'] == 'weekly'){
                    $next_payment_date = date( "m/d/Y", strtotime( "$newdate +7 day" ) );
                }else if($value['service_payment_type'] == 'bi-weekly'){
                    $next_payment_date = date( "m/d/Y", strtotime( "$newdate +14 day" ) );
                }else if ($value['service_payment_type'] == 'monthly'){
                    $next_payment_date = date( "m/d/Y", strtotime( "$newdate +1 month" ) );
                }else{
                    $next_payment_date = '';
                }
                //echo $next_payment_date;
                Bookings::where(['id'=> $value['id']])
                           ->update(['next_payment_date' => $next_payment_date, 'payment_request' => '0']);

                UserPayments::create([
                        'user_id' => $value['user_id'],
                        'booking_id' => $value['id']
                ]);

                /*
                * SEND EMAIL TO USER
                */
                if(!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'localhost'))){
                    Mail::to([$value['email']])->send(new RecurringPaymentUser($value));
                }
                // echo "Payment Deducted for user Id ==> ". $value['user_id']." of Amount ". $value['service_amount']."<br />";
            }            
        }     

        $msg = array(
            'status'  => 'success',
            'message' => 'Cron Job Completed.'
        );
        return response()->json($msg);      
   } 

   public function flushBookingData($request) {
        $request->session()->put('booking[service_type]', '');
        $request->session()->put('booking[service_address]', '');
        $request->session()->put('booking[service_categories]', '');
        $request->session()->put('booking[service_day]', '');             
        $request->session()->put('booking[service_time]', ''); 
        $request->session()->put('booking[service_laundress]', ''); 
        $request->session()->put('booking[service_package]', ''); 
        $request->session()->put('booking[service_job_details]', ''); 
        $request->session()->put('booking[service_folding_details]', ''); 
        $request->session()->put('booking[service_hanging_details]', ''); 
        $request->session()->put('booking[service_washing_details]', ''); 
        $request->session()->put('booking[service_amount]', '');
        $request->session()->put('booking[service_quantity]', '');
        $request->session()->put('booking[service_description]', ''); 
        $request->session()->put('booking[service_payment_type]', ''); 
        $request->session()->put('booking[user_name]', ''); 
        $request->session()->put('booking[user_email]', ''); 
        $request->session()->put('booking[user_address]', ''); 
        $request->session()->put('booking[user_city]', ''); 
        $request->session()->put('booking[user_state]', ''); 
        $request->session()->put('booking[user_zip]', ''); 
        $request->session()->put('booking[user_country]', ''); 
        $request->session()->put('booking[card_name]', ''); 
        $request->session()->put('booking[card_number]', ''); 
        $request->session()->put('booking[card_expiry_month]', ''); 
        $request->session()->put('booking[card_expiry_year]', ''); 
   }
}
