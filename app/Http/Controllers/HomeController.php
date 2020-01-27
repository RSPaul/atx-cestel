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

class HomeController extends Controller {
    

    public function register(Request $request) {

    	$data = $request->all();

    	$request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    	
    	$data['services'] = serialize($data['services']);
    	$data['password'] = Hash::make($data['password']);
    	$data['status'] = 0;
    	try {
        	$user = User::create($data);
        	
	    } catch (\Illuminate\Database\QueryException $exception) {
		    // You can check get the details of the error using `errorInfo`:
		    $errorInfo = $exception->errorInfo;

		    print_r($errorInfo);
		    die();
		}
        die();
        return redirect('/register')->with('status', 'Registered successfully.');
    }
}
