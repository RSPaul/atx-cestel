<?php

namespace App\Http\Controllers\Admin;

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

class AdminController extends Controller
{
    public function __construct() {
        $this->middleware(['auth','verified', 'admin']);
    }

    public function dashboard(Request $request) {
    	$profile = User::where(['id' => Auth::user()->id])->first();
    	return view('admin.dashboard')->with([ "profile" => $profile]);
    }

    public function users(Request $request) {
    	$type = $request->type;
    	$users = User::where(['user_type' => $type])
    					->get();
    	return view('admin.users')->with([ "users" => $users, 'type' => $type]);
    }

    public function verifyUser(Request $request) {
        try {
            User::where(['id' => $request->id])
                        ->update([
                            'status' => 1
                        ]);
            $response = array('success' => true,
                              'message' => 'User verified.');
        }catch(Exception $e) {
            $response = array('success' => false,
                              'message' => $e->getMessage());
        }
        return response()->json($response);
    }

    public function bookings(){

        $all_bookings = array();

        $all_bookings = Bookings::all();
        return view('admin.bookings')->with([ "all_bookings" => $all_bookings]);

    }

    public function bookingDetails(Request $request){

        $id = $request->id;
        
        return view('admin.bookings_details');
        //die($id);

    }
}
