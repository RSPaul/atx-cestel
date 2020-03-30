<?php

namespace App\Http\Controllers\Laundress;

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
use App\PaymentDetails;
use App\PaymentRequests;
use Carbon\Carbon;
use App\Mail\PaymentRequestAdmin;
use App\Mail\PaymentRequest;
use App\Mail\BookingDeclinedUser;

class LaundressController extends Controller
{	
	public function __construct() {
        $this->middleware(['auth','verified', 'laundress'],  ['except' => ['get']]);
         Stripe::setApiKey(env('STRIPE_SECRET'));
        //Stripe::setApiKey(env('STRIPE_PUBLISH'));
    }

    public function profile(Request $request) {
    	$profile = User::where(['id' => Auth::user()->id])->first();
    	return view('laundress.dashboard')->with([ "profile" => $profile]);
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
        return view('laundress.dashboard')->with([ "profile" => $profile, "tab_id" => $tab_id]);
    }

    public function schedule(Request $request) {
        $next_week_bookings = array();
        $all_bookings = array();
        $currentdate = date('m/d/Y');
        $thisWeekDate = date( "m/d/Y", strtotime( "$currentdate +7 day" ) ); 

        $bookings = Bookings::where(['service_laundress' => Auth::user()->id])
                    ->where(['bookings.status' => 'new'])
                    ->join('users', 'users.id', '=', 'bookings.user_id')
                    ->select(DB::raw('bookings.*, users.first_name, users.last_name, users.address, users.city_state'))
                    ->where('bookings.service_day', '=', date('m/d/Y'))
                    ->get();

        $next_week_bookings = Bookings::where(['service_laundress' => Auth::user()->id])
                    ->where(['bookings.status' => 'new'])
                    ->join('users', 'users.id', '=', 'bookings.user_id')
                    ->select(DB::raw('bookings.*, users.first_name, users.last_name, users.address, users.city_state'))
                    ->where('bookings.service_day', '>', date('m/d/Y'))
                    ->where('bookings.service_day', '<', $thisWeekDate)
                    ->get();

        $all_bookings = Bookings::where(['service_laundress' => Auth::user()->id])
                    ->where(['bookings.status' => 'new'])
                    ->join('users', 'users.id', '=', 'bookings.user_id')
                    ->select(DB::raw('bookings.*, users.first_name, users.last_name, users.address, users.city_state'))
                    ->get();

        $past_bookings = Bookings::where(['service_laundress' => Auth::user()->id])
                    ->where('bookings.service_day', '<', date('m/d/Y'))
                    ->orWhere('bookings.status', 'canceled')
                    ->orWhere('bookings.status', 'declined')
                    ->join('users', 'users.id', '=', 'bookings.user_id')
                    ->select(DB::raw('bookings.*, users.first_name, users.last_name, users.address, users.city_state'))
                    ->get();

        return response()->json(['bookings'=> array('today' => $bookings, 'next_week' => $next_week_bookings, 'all_bookings' => $all_bookings, 'past_bookings' => $past_bookings)]);
    }

    public function viewscheduleListCustom(Request $request){
             $data = $request->all();
             $allBooking = array();
             //print_r($data);
             $to_date = date( "m/d/Y", strtotime( $data['to_date'] ) );
             $from_date = date( "m/d/Y", strtotime( $data['from_date'] ) );
             $allBooking = Bookings::where(['service_laundress' => Auth::user()->id])
                    ->where(['bookings.status' => 'new'])
                    ->join('users', 'users.id', '=', 'bookings.user_id')
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

        $allBooking = Bookings::where(['service_laundress' => Auth::user()->id])
                    ->where(['bookings.status' => 'new'])
                    ->join('users', 'users.id', '=', 'bookings.user_id')
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

    public function earningsByWeek(Request $request) {
        $now = Carbon::now();
        $now->startOfWeek(Carbon::MONDAY);
        $weekStartDate = $now->startOfWeek()->format('m/d/Y');
        $weekEndDate = $now->endOfWeek()->format('m/d/Y');

        $bookings = Bookings::where(['service_laundress' => Auth::user()->id])
                        //->orWhere('status', 'completed')
                        ->whereBetween('service_day', [$weekStartDate, $weekEndDate])
                            ->get();
        $washing = array();
        $iorning = array();
        $bedMaking = array();
        $organizing = array();
        $packing = array();


        
        //get earnings by service type
        foreach($bookings as $booking) {


            if($booking->status == 'new' || $booking->status == 'completed' ){
                $allquantity = unserialize($booking->service_quantity);
                $categories = unserialize($booking->service_categories);
                if(in_array('Washing', $categories)) {
                    $pricewashing = env('WASHING_PRICE');
                    array_push($washing, round(($pricewashing) * 90 / 100, 2));
                }
                if(in_array('Ironing', $categories)) {
                    $pricewashing = env('IRONING_PRICE');
                    $ironingamount = $pricewashing * $allquantity['ironing'];
                    array_push($iorning, round(($ironingamount) * 90 / 100, 2));
                }
                if(in_array('BedMaking', $categories)) {
                    $pricebedmaking = env('BEDMAKING_PRICE');
                    $bedmakingamount = $pricebedmaking * $allquantity['beds'];
                    array_push($bedMaking, round(($bedmakingamount) * 90 / 100, 2));
                }
                if(in_array('Organizing', $categories)) {
                    $priceorganising = env('ORGANIZING_PRICE');
                    array_push($organizing, round(($priceorganising) * 90 / 100, 2));
                }
                if(in_array('Packing', $categories)) {
                    array_push($packing, round(($booking->service_amount - $booking->service_tax) * 90 / 100, 2));
                }
            }    
        }
        $weekEarnings = array(
                            array('name'=> 'Washing', 'amount' => array_sum($washing)),
                            array('name'=> 'Ironing', 'amount' => array_sum($iorning)),
                            array('name'=> 'Bed Making', 'amount' => array_sum($bedMaking)),
                            array('name'=> 'Organizing', 'amount' => array_sum($organizing)),
                            array('name'=> 'Packing', 'amount' => array_sum($packing))
                        );
        $totalEarning = array_sum($washing) + array_sum($iorning) + array_sum($bedMaking) + array_sum($organizing) + array_sum($packing);
        return response()->json(['weekEarnings' => $weekEarnings, 'totalEarning' => $totalEarning, 'weekStart' => $now->startOfWeek()->format('m/d'), 'weekEnd' => $now->endOfWeek()->format('m/d')]);
    }

    public function declineBooking(Request $request) {
        $id = (int) $request->id;
        $booking = DB::table('bookings')
            ->where('bookings.id', $id)
            ->where('bookings.service_laundress', Auth::user()->id)
            ->first();

        $data = array();
        if($booking) {
            $data["status"] = 'declined';
            Bookings::where(['id' => $booking->id ])
                    ->update($data);
            /*
            * TODO: SEND EMAIL
            */
            $user = User::where(['id' => $booking->user_id ])->first();
            if(!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'localhost'))){
                Mail::to($user->email)
                    ->send(new BookingDeclinedUser($user));
            }

        } else {
            return response()->json(['response' => "Booking not found!", "status" => false]);
        }
    }

    public function updateAccount(Request $request) {

        $msg = "";
        $data = $request->all();
        $details = PaymentDetails::where(['user_id' => Auth::user()->id])->first();
        if($details) {
            
            if($details->account_number != $data['account_number']) {
                $validatedData = $request->validate([
                    'account_number' => 'required|string|unique:payment_details',
                ]);
            }
            $extra_data = unserialize($details->extra_data);
            try {
                //create file
                if($data['front'] && $data['front'] !='' ) {
                    $fp = fopen($data['front'], 'r');
                    $document_front = \Stripe\File::create([
                      'purpose' => 'identity_document',
                      'file' => $fp
                    ]);                    
                    $document_front_id = $document_front->id;
                    $data['document_front_id'] = $document_front_id;
                } else  {
                    $document_front_id = $data['document_front_id'];
                }
                if($data['back'] && $data['back'] !='' ) {
                    $fp = fopen($data['back'], 'r');
                    $document_back = \Stripe\File::create([
                      'purpose' => 'identity_document',
                      'file' => $fp
                    ]);                    
                    $document_back_id = $document_back->id;
                    $data['document_back_id'] = $document_back_id;
                } else {
                    $document_back_id = $data['document_back_id'];
                    $data['document_back_id'] = $document_back_id;
                }

                $account = \Stripe\Account::update(
                  $details->account,
                  [
                    'external_account' => [
                        'object' => 'bank_account',
                        'country' => 'US',
                        'currency' => 'usd',
                        'routing_number' => $data['routing_number'],
                        'account_number' => $data['account_number'],
                        'account_holder_name' => Auth::user()->name,
                    ],
                    'individual' => [
                        'first_name' => Auth::user()->first_name,
                        'last_name' => Auth::user()->last_name,
                        'email' => Auth::user()->email,
                        'phone' => $data['phone'],
                        //'id_number' => $data['id_number'],
                       // 'ssn_last_4' => $data['ssn_last_4'],
                        'address' => [
                            'line1' => $data['line1'],
                            'line2' => $data['line2'],
                            'city' => $data['city'],
                            'state' => $data['state'],
                            'country' => $data['country'],
                            'postal_code' => $data['postal_code'],
                        ],
                        'dob' => [
                            'day' => $data['day'],
                            'month' => $data['month'],
                            'year' => $data['year'],
                        ]//,
                        // 'verification' => [
                        //     'document' => [
                        //         'front' => ($data['front'] && $data['front'] !='') ? $document_front_id : '',
                        //         'back' => ($data['back'] && $data['back'] !='') ? $document_back_id : ''
                        //     ]
                        // ]
                    ],
                    'business_profile' => [
                        'url' => $data['url'],
                        'mcc' => $data['mcc']
                    ],
                  ]
                );
                $data['account_id'] = $account->external_accounts->data[0]->id;
                $data['account'] = $account->external_accounts->data[0]->account;
                $data['bank_name'] = $account->external_accounts->data[0]->bank_name;
                $data['last4'] = $account->external_accounts->data[0]->last4;
                $data['request_data'] = serialize($data);

                $details = PaymentDetails::find($details->id);
                $details->account_id = $account->external_accounts->data[0]->id;
                $details->account = $account->external_accounts->data[0]->account;
                $details->bank_name = $account->external_accounts->data[0]->bank_name;
                $details->last4 = $account->external_accounts->data[0]->last4;
                $details->request_data = $data['request_data'];

                $details->save();

                return response()->json(['message' => "Payment Information updated successfully!", "status" => true]);

            } catch (\Stripe\Error\RateLimit $e) {
                $msg = $e->getMessage();              
            } catch (\Stripe\Error\InvalidRequest $e) {
                $msg = $e->getMessage();
            } catch (\Stripe\Error\Authentication $e) {
                $msg = $e->getMessage();
            } catch (\Stripe\Error\ApiConnection $e) {
                $msg = $e->getMessage();
            } catch (\Stripe\Error\Base $e) {
                $msg = $e->getMessage();
            } catch (Exception $e) {
                $msg = $e->getMessage();
            }
            
            return response()->json(['message' => $msg, "status" => false]);
        
        } else {

            $validatedData = $request->validate([
                'account_number' => 'required|string|unique:payment_details',
            ]);

            try {

                //create file
                if($data['front'] && $data['front'] !='') {
                    $fp = fopen($data['front'], 'r');
                    $document_front = \Stripe\File::create([
                      'purpose' => 'identity_document',
                      'file' => $fp
                    ]);                    
                    $document_front_id = $document_front->id;
                    $data['document_front_id'] = $document_front_id;
                } else  {
                    $document_front_id = $data['document_front_id'];
                }
                if($data['back'] && $data['back'] !='') {
                    $fp = fopen($data['back'], 'r');
                    $document_back = \Stripe\File::create([
                      'purpose' => 'identity_document',
                      'file' => $fp
                    ]);                    
                    $document_back_id = $document_back->id;
                    $data['document_back_id'] = $document_back_id;
                } else {
                    $document_back_id = $data['document_back_id'];
                    $data['document_back_id'] = $document_back_id;
                }

                $account = \Stripe\Account::create([
                    'country' => 'US',
                    'type' => 'custom',
                    'requested_capabilities' => ["transfers", "card_payments"],
                    'business_type' => 'individual',
                    'individual' => [
                        'first_name' => Auth::user()->first_name,
                        'last_name' => Auth::user()->last_name,
                        'email' => Auth::user()->email,
                        'phone' => $data['phone'],
                        'id_number' => $data['id_number'],
                        'ssn_last_4' => $data['ssn_last_4'],
                        'address' => [
                            'line1' => $data['line1'],
                            'line2' => $data['line2'],
                            'city' => $data['city'],
                            'state' => $data['state'],
                            'country' => $data['country'],
                            'postal_code' => $data['postal_code'],
                        ],
                        'dob' => [
                            'day' => $data['day'],
                            'month' => $data['month'],
                            'year' => $data['year'],
                        ],
                        'verification' => [
                            'document' => [
                                'front' => ($data['front'] && $data['front'] !='') ? $document_front_id: '',
                                'back' => ($data['back'] && $data['back'] !='') ? $document_back_id : ''
                            ]
                        ]
                    ],
                    'business_profile' => [
                        'url' => $data['url'],
                        'mcc' => $data['mcc']
                    ],
                    'external_account'=> [
                        'object' => 'bank_account',
                        'country' => 'US',
                        'currency' => 'usd',
                        'routing_number' => $data['routing_number'],
                        'account_number' => $data['account_number'],
                        'account_holder_name' => Auth::user()->first_name.' '. Auth::user()->last_name,
                    ],
                    'tos_acceptance' => [
                        'date' => time(),
                        'ip' => $_SERVER['REMOTE_ADDR']
                    ]
                ]);

                $data['user_id'] = Auth::user()->id;
                $data['account_type'] = 'custom';
                $data['account_id'] = $account->external_accounts->data[0]->id;
                $data['account'] = $account->external_accounts->data[0]->account;
                $data['bank_name'] = $account->external_accounts->data[0]->bank_name;
                $data['last4'] = $account->external_accounts->data[0]->last4;
                $data['request_data'] = serialize($data);

                $details = new PaymentDetails($data);
                $details->save();
                
                return response()->json(['message' => 'Payment Information saved successfully!', "status" => true]);

            } catch (\Stripe\Error\RateLimit $e) {
                $msg = $e->getMessage();              
            } catch (\Stripe\Error\InvalidRequest $e) {
                $msg = $e->getMessage();
            } catch (\Stripe\Error\Authentication $e) {
                $msg = $e->getMessage();
            } catch (\Stripe\Error\ApiConnection $e) {
                $msg = $e->getMessage();
            } catch (\Stripe\Error\Base $e) {
                $msg = $e->getMessage();
            } catch (Exception $e) {
                $msg = $e->getMessage();
            }

            return response()->json(['message' => $msg, "status" => false]);
        }
    }

    public function getAccount(Request $request) {
        $details = PaymentDetails::where(['user_id' => Auth::user()->id])->first();
        $request_data = array('account_type' => '',
                                'name' => '',
                                'routing_number' => '',
                                'account_number' => '',
                                'day' => '',
                                'month' => '',
                                'year' => '',
                                'line1' => '',
                                'line2' => '',
                                'phone' => '',
                                'city' => '',
                                'state' => '',
                                'country' => '',
                                'postal_code' => '',
                                'mcc' => '',
                                'url' => '',
                                'id_number' => '',
                                'ssn_last_4' => '',
                                'front_pic' => '',
                                'back_pic' => '',
                                'front' => '',
                                'back' => '',
                                'document_front_id' => '',
                                'document_back_id' => '');
        if($details) {
            $request_data = unserialize($details->request_data);
        }
        $bookings = DB::table('bookings')
            ->where('bookings.service_laundress', Auth::user()->id)
            ->whereIn('bookings.status', ['completed'])
            ->join('users', 'users.id', '=', 'bookings.user_id')
                    ->select(DB::raw('bookings.*, users.first_name, users.last_name, users.address, users.city_state'))
            ->orderBy('bookings.id', 'desc')
            ->get();
        return response()->json(['message' => $details, "status" => true, 'bookings' => $bookings, 'extra_data' => $request_data]);
    }

    public function requestPayment(Request $request) {
        if ($request->has("book_ids")) {
            
            $ids = $request->book_ids;
            $bookings = DB::table('bookings')
                ->where(['bookings.service_laundress' => Auth::user()->id, 'bookings.payment_request' => 0])
                ->whereIn('bookings.id', $ids)
                ->select('bookings.*')
                ->get();

            if( count($bookings)) {
                $payment = 0;
                $pids = array();
                foreach ($bookings as $key => $book) {
                    if($book->status == "completed") {
                        $payment = $payment + (($book->service_amount - $book->service_tax) * 90 / 100);
                        array_push($pids, $book->id);
                    }
                }
                

                if($payment) {
                    $req = new PaymentRequests();
                    $req->laundress_id = Auth::user()->id;
                    $req->amount = round($payment, 2);
                    $req->booking_ids = serialize($pids);
                    $req->status = "requested";
                    $req->save();

                    Bookings::whereIn('id', $pids)
                                    ->update(["payment_request" => '1']);

                   /*
                   * TODO: SEND MAIL TO ADMIN AND LAUNDRESS
                   */
                   if(!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'localhost'))){
                        Mail::to(Auth::user()->email)->send(new PaymentRequest(Auth::user(),round($payment, 2)));
                        Mail::to(env('ADMIN_EMAIL'))->send(new PaymentRequestAdmin(Auth::user(),round($payment, 2)));
                    }
                }

                return response()->json(['response' => "Request sent successfully!", "status" => true]);
            } else {
                return response()->json(['response' => "No New Bookings found!", "status" => false]);
            }        

        }
    }

    public function uploadImage(Request $request) {
        try {
            $data = $request->image;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $image_name = time().'.png';
            $path = public_path() . "/uploads/profiles/" . $image_name;

            file_put_contents($path, $data);

            return response()->json(['path' => $path, "status" => true]);

        } catch (Exception $e) {
            $msg = $e->getMessage();
            return response()->json(['message' => $msg, "status" => false]);
        }
    }

    public function is_base64($s) {
      return (bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s);
    }
}
