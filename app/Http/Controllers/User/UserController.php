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
use App\Bookings;

class UserController extends Controller
{	
	public function __construct() {
        $this->middleware(['auth','verified', 'user'],  ['except' => ['get', 'updateProile']]);
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
                    ->join('users', 'users.id', '=', 'bookings.service_laundress')
                    ->select(DB::raw('bookings.*, users.first_name, users.last_name, users.address, users.city_state'))
                    ->where('bookings.service_day', '<=', date('m/d/Y'))
                    ->get();

        $next_week_bookings = Bookings::where(['user_id' => Auth::user()->id])
                    ->join('users', 'users.id', '=', 'bookings.service_laundress')
                    ->select(DB::raw('bookings.*, users.first_name, users.last_name, users.address, users.city_state'))
                    ->where('bookings.service_day', '>', date('m/d/Y'))
                    ->get();

        $all_bookings = Bookings::where(['user_id' => Auth::user()->id])
                    ->join('users', 'users.id', '=', 'bookings.service_laundress')
                    ->select(DB::raw('bookings.*, users.first_name, users.last_name, users.address, users.city_state'))
                    ->get();
                    

        return response()->json(['bookings'=> array('today' => $bookings, 'next_week' => $next_week_bookings, 'all_bookings' => $all_bookings)]);
    }

}
