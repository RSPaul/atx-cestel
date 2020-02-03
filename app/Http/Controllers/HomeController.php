<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;
use Mail;
use App\User;
use App\UserCards;
use App\Mail\VerificationEmail;

class HomeController extends Controller {
    
    public function register(Request $request) {

    	$data = $request->all();

    	$request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    	
    	$data['services'] = serialize($data['services']);
    	$data['password'] = Hash::make($data['password']);
    	$data['status'] = 0;
    	try {
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


            Mail::to([$user->email])->send(new VerificationEmail($user, $link));
            return redirect('/verify')->with('status', 'Registered successfully.');
        } catch (\Illuminate\Database\QueryException $exception) {
            // You can check get the details of the error using `errorInfo`:
            $errorInfo = $exception->errorInfo;
            return redirect('/register')->with('status',$errorInfo);
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

            $request->session()->put('service_type', $request->service_type);
            $request->session()->put('service_categories', $request->service_categories);
            $request->session()->put('service_beds', $request->service_beds); 
            $request->session()->put('service_day', $request->service_day);             
            $request->session()->put('service_time', $request->service_time); 
            $request->session()->put('service_laundress', $request->service_laundress); 
            $request->session()->put('service_package', $request->service_package); 
            $request->session()->put('service_job_details', $request->service_job_details); 
            $request->session()->put('service_folding_details', $request->service_folding_details); 
            $request->session()->put('service_hanging_details', $request->service_hanging_details); 
            $request->session()->put('service_washing_details', $request->service_washing_details); 
            $request->session()->put('service_amount', $request->service_amount); 
            $request->session()->put('service_description', $request->service_description); 
            $request->session()->put('service_payment_type', $request->service_payment_type); 
            $request->session()->put('user_name', $request->user_name); 
            $request->session()->put('user_email', $request->user_email); 
            $request->session()->put('user_address', $request->user_address); 
            $request->session()->put('user_city', $request->user_city); 
            $request->session()->put('user_state', $request->user_state); 
            $request->session()->put('user_zip', $request->user_zip); 
            $request->session()->put('user_country', $request->user_country); 
            $request->session()->put('card_name', $request->card_name); 
            $request->session()->put('card_number', $request->card_number); 
            $request->session()->put('card_expiry_month', $request->card_expiry_month); 
            $request->session()->put('card_expiry_year', $request->card_expiry_year); 
            $request->session()->put('card_security_code', $request->card_security_code); 
        } else {
            $service_type = $request->session()->get('service_type');
            $service_categories = $request->session()->get('service_categories');
            $service_beds = $request->session()->get('service_beds'); 
            $service_day = $request->session()->get('service_day');             
            $service_time = $request->session()->get('service_time'); 
            $service_laundress = $request->session()->get('service_laundress'); 
            $service_package = $request->session()->get('service_package'); 
            $service_job_details = $request->session()->get('service_job_details'); 
            $service_folding_details = $request->session()->get('service_folding_details'); 
            $service_hanging_details = $request->session()->get('service_hanging_details'); 
            $service_washing_details = $request->session()->get('service_washing_details'); 
            $service_amount = $request->session()->get('service_amount'); 
            $service_description = $request->session()->get('service_description'); 
            $service_payment_type = $request->session()->get('service_payment_type'); 
            $user_name = $request->session()->get('user_name'); 
            $user_email = $request->session()->get('user_email'); 
            $user_address = $request->session()->get('user_address'); 
            $user_city = $request->session()->get('user_city'); 
            $user_state = $request->session()->get('user_state'); 
            $user_zip = $request->session()->get('user_zip'); 
            $user_country = $request->session()->get('user_country'); 
            $card_name = $request->session()->get('card_name'); 
            $card_number = $request->session()->get('card_number'); 
            $card_expiry_month = $request->session()->get('card_expiry_month'); 
            $card_expiry_year = $request->session()->get('card_expiry_year'); 
            $card_security_code = $request->session()->get('card_security_code'); 
        }
        $profile = array();
        if (Auth::check()) {
            $profile = User::where(['id' => Auth::user()->id])->first(); 
        }
        // echo "<pre>";
        // print_r($request->session());die();
        return view('book')->with([ "profile" => $profile]);
    }
}
