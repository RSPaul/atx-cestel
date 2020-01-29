<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;
use Mail;
use App\User;

class UserController extends Controller
{	
	public function __construct() {
        $this->middleware(['auth','verified']);
    }

    public function profile(Request $request) {
    	$profile = User::where(['id' => Auth::user()->id])->first();
    	return view('user.profile')->with([ "profile" => $profile]);
    }
}
