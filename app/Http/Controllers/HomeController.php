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


            Mail::to($user->email])->send(new VerificationEmail($user, $link));
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
}
