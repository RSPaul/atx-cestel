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
        $this->middleware(['auth','verified', 'user'],  ['except' => ['get']]);
    }

    public function profile(Request $request) {
    	$profile = User::where(['id' => Auth::user()->id])->first();
    	return view('user.profile')->with([ "profile" => $profile]);
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
}
