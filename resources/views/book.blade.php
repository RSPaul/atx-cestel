@extends('layouts.main')
@section('content')
<section class="heading_top">
   <div class="container">
      <ul class="nav nav-tabs nav-tabs-dropdown horizontal" role="tablist">
         <li role="presentation" class="active">
         	<a href="#services" aria-controls="load" role="tab" data-toggle="tab"><span class="bor_rad">
         		<img src="{{asset('img/services.png')}}" alt="services"/></span> <b>Services</b>
         	</a>
         </li>
         <li role="presentation">
         	<a href="#when" aria-controls="account" role="tab" data-toggle="tab"><span class="bor_rad">
         		<img src="{{asset('img/schedule.png')}}" alt="schedule"/></span> <b>When</b>
         	</a>
         </li>
         <li role="presentation">
         	<a href="#contact" aria-controls="schedule" role="tab" data-toggle="tab"><span class="bor_rad">
         		<img src="{{asset('img/contact.png')}}" alt="contact"/></span> <b>Contact</b>
         	</a>
         </li>
         <li role="presentation">
         	<a href="#loading" aria-controls="loading" role="tab" data-toggle="tab"><span class="bor_rad">
         		<img src="{{asset('img/load.png')}}" alt="load"/></span> <b>Load</b></a>
         	</li>
         <li role="presentation">
         	<a href="#detail" aria-controls="logout" role="tab" data-toggle="tab"><span class="bor_rad">
         		<img src="{{asset('img/details.png')}}" alt="details"/></span> <b>Details</b>
         	</a>
         </li>
      </ul>
   </div>
</section>
<!-- Start Team section-->
<section class="bepart_bx">
   <div class="container">
      <div class="row ">
         <div class="border_bx_acc">
            <div class="col-md-12">
	            <form name="service_form" action="{{ route('booking') }}" method="POST" id="bookingForm">
	            	@csrf
	               <!-- Tab panes -->
	               <div class="tab-content">
	                  <div role="tabpanel" class="tab-pane active" id="services">
	                     <section class="heading_top selectpack">
	                        <div class="container">
	                           <h2>Please select your folding package:</h2>
	                           <p>All folding packages include folding, putting away and hanging of already washed and dried clothes.</p>
	                        </div>
	                     </section>
	                     <div class="sub-heading">
	                        <h3>Folding</h3>
	                        <img src="{{asset('img/folding.png')}}" alt="folding"/>
	                     </div>
	                     <div class="fold_bx">
	                        <div class="row">
	                           <div class="col-md-4 text-center fold">
	                              <h4>Petite</h4>
	                              <div class="hov_bx">
	                                 <div class="imglist">
	                                    <img src="{{asset('img/basket.png')}}" alt="basket"/>
	                                    <img src="{{asset('img/basket.png')}}" alt="basket"/>
	                                    <img src="{{asset('img/basket.png')}}" alt="basket"/>
	                                 </div>
	                                 <h3>1-3 BASKETS</h3>
	                                 <div class="memb_info">
	                                    <img src="{{asset('img/member.png')}}" alt="member"/>		
	                                    <p>Recommended for a family of </p>
	                                    <h3>2-3 members</h3>
	                                 </div>
	                              </div>
	                              <input type="radio" name="service_type" class="btn btn-primary sel" value="Petite" @if(Session::get('booking[service_type]') == 'Petite') checked @endif/>Select
	                           </div>
	                           <div class="col-md-4 text-center">
	                              <h4>Median</h4>
	                              <div class="hov_bx">
	                                 <div class="imglist">
	                                    <img src="{{asset('img/basket.png')}}" alt="basket"/>
	                                    <img src="{{asset('img/basket.png')}}" alt="basket"/>
	                                    <img src="{{asset('img/basket.png')}}" alt="basket"/>
	                                 </div>
	                                 <h3>1-3 BASKETS</h3>
	                                 <div class="memb_info">
	                                    <img src="{{asset('img/member.png')}}" alt="member"/>		
	                                    <p>Recommended for a family of </p>
	                                    <h3>2-3 members</h3>
	                                 </div>
	                              </div>
	                              <input type="radio" name="service_type" class="btn btn-primary sel" value="Median" @if(Session::get('booking[service_type]') == 'Median') checked @endif/>Select
	                           </div>
	                           <div class="col-md-4 text-center">
	                              <h4>Grand</h4>
	                              <div class="hov_bx">
	                                 <div class="imglist">
	                                    <img src="{{asset('img/basket.png')}}" alt="basket"/>
	                                    <img src="{{asset('img/basket.png')}}" alt="basket"/>
	                                    <img src="{{asset('img/basket.png')}}" alt="basket"/>
	                                 </div>
	                                 <h3>1-3 BASKETS</h3>
	                                 <div class="memb_info">
	                                    <img src="{{asset('img/member.png')}}" alt="member"/>		
	                                    <p>Recommended for a family of </p>
	                                    <h3>2-3 members</h3>
	                                 </div>
	                              </div>
	                              <input type="radio" name="service_type" class="btn btn-primary sel" value="Grand"  @if(Session::get('booking[service_type]') == 'Grand') checked @endif/>
	                           </div>
	                        </div>
	                     </div>
	                     <div class="next_serv">
	                        <section class="heading_top selectpack">
	                           <div class="container">
	                              <h2>Select your add on service:</h2>
	                              <p>*All of our pricing is flat rate, the only per hour rate is organizing. We also do not charge a trip fee *</p>
	                           </div>
	                        </section>
	                        <div class="row">
	                           <div class="col-md-12">
	                              <ul class="list_opt">
	                                 <li>
	                                    <img src="{{asset('img/washing.png')}}" alt="washing"/>
	                                    <h3>Washing</h3>
	                                    <div class="info">${{env('WASHING_PRICE')}} flat fee </div>
	                                    <input type="checkbox" name="service_categories[]" class="btn btn-primary" value="Washing" @if(!empty(Session::get('booking[service_categories]')) && in_array('Washing', Session::get('booking[service_categories]'))) checked @endif />
	                                 </li>
	                                 <li>
	                                    <img src="{{asset('img/ironing.png')}}" alt="ironing"/>
	                                    <h3>Ironing</h3>
	                                    <div class="info">Number of garments (${{env('IRONING_PRICE')}} per garment) 
	                                    </div>
	                                    <input type="checkbox" name="service_categories[]" class="btn btn-primary" value="Ironing" @if(!empty(Session::get('booking[service_categories]')) && in_array('Ironing', Session::get('booking[service_categories]'))) checked @endif/>
	                                 </li>
	                                 <li>
	                                    <img src="{{asset('img/bedmaking.png')}}" alt="bedmaking"/>
	                                    <h3>Bed Making</h3>
	                                    <div class="info"><input type="text" placeholder="Enter number of beds" name="service_beds" value="{{Session::get('booking[service_beds]')}}" />
	                                    </div>
	                                    <input type="checkbox" name="service_categories[]" class="btn btn-primary" value="BedMaking" @if(!empty(Session::get('booking[service_categories]')) && in_array('BedMaking', Session::get('booking[service_categories]'))) checked @endif/>
	                                 </li>
	                                 <li>
	                                    <img src="{{asset('img/organizing.png')}}" alt="washing"/>
	                                    <h3>Organizing</h3>
	                                    <div class="info">${{env('ORGANIZING_PRICE')}} per hour </div>
	                                    <input type="checkbox" name="service_categories[]" class="btn btn-primary" value="Organizing" @if(!empty(Session::get('booking[service_categories]')) && in_array('Organizing', Session::get('booking[service_categories]'))) checked @endif/>
	                                 </li>
	                                 <!-- 	<li>
	                                    <img src="{{asset('img/packing.png')}}" alt="packing"/>
	                                    <h3>Packing</h3>
	                                    <a href="javascript:void(0);" class="btn btn-primary"/>Select</a>
	                                    </li> -->
	                                    <input type="hidden" value="{{ env('WASHING_PRICE') }}" id="WASHING_PRICE">
	                                    <input type="hidden" value="{{ env('IRONING_PRICE') }}" id="IRONING_PRICE">
	                                    <input type="hidden" value="{{ env('ORGANIZING_PRICE') }}" id="ORGANIZING_PRICE">
	                                    <input type="hidden" value="{{ env('BEDMAKING_PRICE') }}" id="BEDMAKING_PRICE">
	                              </ul>
	                           </div>
	                        </div>
	                        <div class="text-center">
	                           <a href="javascript:void(0);" class="btn btn-primary ctn-btn" data-tab="when"/>Continue</a>
	                        </div>
	                     </div>
	                  </div>
	                  <div role="tabpanel" class="tab-pane" id="when">
	                     <section class="heading_top selectpack">
	                        <div class="container">
	                           <h2>When would you like this service?</h2>
	                        </div>
	                     </section>
	                     <div class="choose_opt">
	                        <div class="row">
	                           <div class="col-md-4">
	                              <label>Choose a day</label>
	                              <div class="form-group">
	                                 <img src="{{asset('img/day.png')}}" alt="day"/>
	                                 <select name="service_day" required id="service_day" >
	                                    <option value="Monday" @if(Session::get('booking[service_day]') == 'Monday') "selected='selected'" @endif>Monday</option>
	                                    <option value="Tuesday" @if(Session::get('booking[service_day]') == 'Tuesday') "selected='selected'" @endif>Tuesday</option>
	                                    <option value="Wednesday" @if(Session::get('booking[service_day]') == 'Wednesday') "selected='selected'" @endif>Wednesday</option>
	                                    <option value="Thursday" @if(Session::get('booking[service_day]') == 'Thursday') "selected='selected'" @endif>Thursday</option>
	                                    <option value="Friday" @if(Session::get('booking[service_day]') == 'Friday') "selected='selected'" @endif>Friday</option>
	                                    <option value="Weekly" @if(Session::get('booking[service_day]') == 'Weekly') "selected='selected'" @endif>Weekly</option>
	                                    <option value="Bi-weekly" @if(Session::get('booking[service_day]') == 'Bi-weekly') "selected='selected'" @endif>Bi-weekly</option>
	                                    <option value="Monthly" @if(Session::get('booking[service_day]') == 'Monthly') "selected='selected'" @endif>Monthly</option>
	                                 </select>
	                              </div>
	                           </div>
	                           <div class="col-md-4">
	                              <label>Choose a Time</label>
	                              <div class="form-group">
	                                 <img src="{{asset('img/day.png')}}" alt="day"/>
	                                 <select name="service_time" id="service_time" required>
	                                    <option value="8:00AM - 7:00PM"  @if(Session::get('booking[service_time]') == '8:00AM - 7:00PM') "selected='selected'" @endif>8:00AM - 7:00PM</option>
	                                    <option value="9:00AM - 10:00PM"  @if(Session::get('booking[service_time]') == '9:00AM - 10:00PM') "selected='selected'" @endif>9:00AM - 10:00PM</option>
	                                 </select>
	                              </div>
	                           </div>
	                           <div class="col-md-4">
	                              <label>Choose a Laundress</label>
	                              <div class="form-group">
	                                 <img src="{{asset('img/day.png')}}" alt="day"/>
	                                 <select name="service_laundress" id="service_laundress" required>
	                                 	<option value="">Select</option>
	                                 	@foreach($laundress as $laundres)
	                                 	<option value="{{$laundres->id}}" @if(Session::get('booking[service_laundress]') == $laundres->id) "selected='selected'" @endif>{{$laundres->first_name}} {{$laundres->last_name}}</option>
	                                 	@endforeach
	                                 </select>
	                              </div>
	                           </div>
	                        </div>
	                     </div>
	                     <div class="text-center">
	                        <a href="javascript:void(0);" class="btn btn-primary ctn-btn" data-tab="contact"/>Continue</a>
	                     </div>
	                  </div>
	                  <div role="tabpanel" class="tab-pane" id="contact">
	                     <section class="heading_top selectpack">
	                        <div class="container">
	                           <h2>Tell us about you & how to contact you?</h2>
	                        </div>
	                     </section>
	                     @if($profile && $profile->id)
	                     	<h3 class="text-center">You are already logged in <a href="javascript:void(0);" class="btn btn-primary ctn-btn text-center" data-tab="loading">Continue</a> to next step.</h3>
	                     @else
	                    <div class="row">
	                        <div class="col-md-6 text-center">
	                           <h3>New Customer</h3>
	                           <div class="form-group">
	                              <label>First Name</label>
	                              <input type="text" name="first_name"  class="form-control" required />
	                           </div>
	                           <div class="form-group">
	                              <label>Last Name</label>
	                              <input type="text" name="last_name"  class="form-control" required/>
	                           </div>
	                           <div class="form-group">
	                              <label>Email Address</label>
	                              <input type="email" name="email"  class="form-control" required/>
	                           </div>
	                           <div class="form-group">
	                              <label>Phone</label>
	                              <input type="text" name="phone"  class="form-control" required/>
	                           </div>
	                           <div class="form-group">
	                              <label>Zipcode</label>
	                              <input type="text" name="zip"  class="form-control" />
	                           </div>
	                           <div class=" btn-rw">	
	                              <input type="submit" class="btn btn-primary" value="Signup" id="signupFormSubmit">
	                           </div>
	                        </div>
	                        <div class="col-md-6 text-center">
	                           <h3>Returning Customer</h3>
	                           <div class=" btn-rw">	
	                              <a href="javascript:void(0);" class="btn btn-primary">Login</a>
	                           </div>
	                        </div>
	                    </div>
	                     @endif
	                  </div>
	                  <div role="tabpanel" class="tab-pane" id="loading">
	                     <section class="heading_top selectpack">
	                        <div class="container">
	                           <h2>Please select your Ironing package:</h2>
	                           <p>All Ironing garments must be clean and be 96% cotton and 67% polyster or greater.</p>
	                        </div>
	                     </section>
	                     <div class="sub-heading">
	                        <h3>Press Packages</h3>
	                        <img src="{{asset('img/folding.png')}}" alt="folding"/>
	                        <p>A folding package must be purchased with this package and A is Carte ironing item</p>
	                     </div>
	                     <div class="fold_bx">
	                        <div class="row">
	                           <div class="col-md-3 text-center fold">
	                              <h4>Press Package 5</h4>
	                              <div class="hov_bx">
	                                 <div class="imglist">
	                                    <img src="{{asset('img/basket.png')}}" alt="basket"/>
	                                 </div>
	                                 <h3>10 Clothing items</h3>
	                                 <p>(Lightly ironed)</p>
	                                 <div class="memb_info">
	                                    <p>	All ironing items must be created than 96% cotton. A folding package must be purchased with this package </p>
	                                 </div>
	                              </div>
	                              <input type="radio" name="service_package" value="p5" @if(Session::get('booking[service_package]') == 'p5') checked @endif>Select
	                           </div>
	                           <div class="col-md-3 text-center fold">
	                              <h4>Press Package 10</h4>
	                              <div class="hov_bx">
	                                 <div class="imglist">
	                                    <img src="{{asset('img/basket.png')}}" alt="basket"/>
	                                 </div>
	                                 <h3>10 Clothing items</h3>
	                                 <p>(Lightly ironed)</p>
	                                 <div class="memb_info">
	                                    <p>	All ironing items must be created than 96% cotton. A folding package must be purchased with this package </p>
	                                 </div>
	                              </div>
	                              <input type="radio" name="service_package" value="p10" @if(Session::get('booking[service_package]') == 'p10') checked @endif>Select
	                           </div>
	                           <div class="col-md-3 text-center fold">
	                              <h4>Press Package 15</h4>
	                              <div class="hov_bx">
	                                 <div class="imglist">
	                                    <img src="{{asset('img/basket.png')}}" alt="basket"/>
	                                 </div>
	                                 <h3>15 Clothing items</h3>
	                                 <p>(Lightly ironed)</p>
	                                 <div class="memb_info">
	                                    <p>	All ironing items must be created than 96% cotton. A folding package must be purchased with this package </p>
	                                 </div>
	                              </div>
	                              <input type="radio" name="service_package" value="p15" @if(Session::get('booking[service_package]') == 'p15') checked @endif>Select
	                           </div>
	                           <div class="col-md-3 text-center fold">
	                              <h4>Press Package 20</h4>
	                              <div class="hov_bx">
	                                 <div class="imglist">
	                                    <img src="{{asset('img/basket.png')}}" alt="basket"/>
	                                 </div>
	                                 <h3>20 Clothing items</h3>
	                                 <p>(Lightly ironed)</p>
	                                 <div class="memb_info">
	                                    <p>	All ironing items must be created than 96% cotton. A folding package must be purchased with this package </p>
	                                 </div>
	                              </div>
	                              <input type="radio" name="service_package" value="p20" @if(Session::get('booking[service_package]') == 'p20') checked @endif>Select
	                           </div>
	                        </div>
	                     </div>
	                     <div class="text-center">
	                        <a href="javascript:void(0);" class="btn btn-primary ctn-btn" data-tab="detail"/>Continue</a>
	                     </div>
	                  </div>
	                  <div role="tabpanel" class="tab-pane" id="detail">
	                     <div class="info_contact">
	                        <h2>All most done just want to make sure we are correct</h2>
	                        <div class="col-rw">
	                           <div class="form-group">
	                              <label>Service Day</label>
	                              <input type="text"  placeholder="08/19/2019" class="form-control" value="{{Session::get('booking[service_day]')}}"  readonly id="service_day_selected" />
	                           </div>
	                           <div class="form-group">
	                              <label>Time</label>
	                              <input type="text"  placeholder="3:00 pm - 5:00 pm" class="form-control" value="{{Session::get('booking[service_time]')}}" readonly  id="service_time_selected" />
	                           </div>
	                           <div class="form-group">
	                              <label>Laundress</label>
	                              <input type="text"  placeholder="Jenny Johnson" class="form-control" value="{{Session::get('booking[service_laundress]')}}" readonly id="service_laundress_selected" />
	                           </div>
	                        </div>
	                        <div class="col2-rw">
	                           <div class="form-group first">
	                              <label>Service Address</label>
	                              <input type="text"  placeholder="1516, S. Brookes Street, Austin, Texas" class="form-control" value="{{$profile->address}}" readonly/>
	                           </div>
	                           <div class="form-group">
	                              <label>Zip Code</label>
	                              <input type="text"  placeholder="74293" class="form-control" value="{{$profile->zip}}" readonly/>
	                           </div>
	                        </div>
	                        <div class="col-rw">
	                           <div class="form-group">
	                              <label>Contact Name</label>
	                              <input type="text"  placeholder="Karen Mars" class="form-control" value="{{$profile->first_name}} {{$profile->last_name}}" readonly/>
	                           </div>
	                           <div class="form-group">
	                              <label>Contact Email</label>
	                              <input type="text"  placeholder="karen@email.com" class="form-control" value="{{$profile->email}}" readonly />
	                           </div>
	                           <div class="form-group">
	                              <label>Contact Phone</label>
	                              <input type="text"  placeholder="597-978-6358" class="form-control" value="{{$profile->phone}}" readonly/>
	                           </div>
	                        </div>
	                        <div class="col-rw">
	                           <div class="form-group">
	                              <label>Pricing</label>
	                              <input type="text"  placeholder="$35.00" class="form-control" value="{{$price}}" id="main_price" readonly />
	                           </div>
	                           <div class="form-group">
	                              <label>Quantity</label>
	                              <input type="number"  placeholder="2-shirt pressed" class="form-control" name="service_quantity" value="{{Session::get('booking[service_quantity]') || 1}}" id="service_quantity" min="1" />
	                           </div>
	                           <div class="form-group">
	                              <label>Total</label>
	                              <input type="text" placeholder="$70.00" class="form-control total_price" value="{{$total_price}}" readonly/>
	                           </div>
	                        </div>
	                        <div class="form-group">
	                           <label>Job details</label>
	                           <textarea class="form-control" name="service_job_details">{{Session::get('booking[service_job_details]')}}</textarea>
	                        </div>
	                        <div class=" btn-rw">	
	                           <a href="javascript:void(0);" class="btn btn-primary ctn-btn" data-tab="services">Edit Service</a>
	                        </div>
	                        <h2>Quality is important to us, may need more service details.</h2>
	                        <div class="bor_bx">
	                           <h3>FOLDING</h3>
	                           <p>Do you have a folding preference and will there be anything off limits? If so, please remove it before we begin service or specify details below.</p>
	                           <textarea class="form-control" name="service_folding_details">{{Session::get('booking[service_folding_details]')}}</textarea>
	                        </div>
	                        <div class="bor_bx">
	                           <h3>HANGING</h3>
	                           <p>Please set aside the clothing you want to be hung and notify your Cesta Laundress. Have a specific instructions, please detail below.<br> 
	                              <i>Please note that you are expected to provide your own hangers</i>
	                           </p>
	                           <textarea class="form-control" name="service_hanging_details">{{Session::get('booking[service_hanging_details]')}}</textarea>
	                        </div>
	                        <div class="bor_bx">
	                           <h3>WASHING</h3>
	                           <p>Please set aside and notify your Laundress of any delicate garments and or air dry only garments. Have specific washing instructions, and or how you want your clothes separated, please detail below.<br> 
	                              <i>Please note that you are expected to provide your own hangers</i>
	                           </p>
	                           <textarea class="form-control" name="service_washing_details">{{Session::get('booking[service_washing_details]')}}</textarea>
	                        </div>
	                        <div class="alert alert-danger" id="msgError"></div>
	                        <div class="alert alert-success" id="msgSuccess"></div>
	                        <div class="form_payment">
	                           <div class="row">
	                              <div class="col-md-6">
	                                 <h3>Payment Details</h3>
	                                 <div class="form-group row">
	                                    <label for="staticEmail" class="col-sm-3 col-form-label"><i>*</i> Amount</label>
	                                    <div class="col-sm-9">
	                                       <div class="input-group mb-3">
	                                          <div class="input-group-prepend">
	                                             <span class="input-group-text" id="basic-addon1">$</span>
	                                          </div>
	                                          <input type="text" class="form-control total_price" placeholder="Amount" aria-label="Username" aria-describedby="basic-addon1" name="service_amount" value="{{$total_price}}" readonly>
	                                       </div>
	                                    </div>
	                                 </div>
	                                 <div class="form-group row">
	                                    <label for="inputPassword" class="col-sm-3 col-form-label"><i>*</i> Description</label>
	                                    <div class="col-sm-9">
	                                       <textarea class="form-control" name="service_description">{{Session::get('booking[service_description]')}}</textarea>
	                                    </div>
	                                 </div>
	                                 <div class="form-group row">
	                                    <label for="inputPassword" class="col-sm-3 col-form-label"><i>*</i> Payment Type</label>
	                                    <div class="col-sm-9">
	                                       <label><input name="service_payment_type" checked type="radio" value="one_time" /> One Time</label>
	                                       <label><input name="service_payment_type" type="radio" value="recurring" /> Recurring</label>
	                                    </div>
	                                 </div>
	                                 <h3>Your Information</h3>
	                                 <div class="form-group row">
	                                    <label for="inputPassword" class="col-sm-3 col-form-label"><i>*</i> Name</label>
	                                    <div class="col-sm-9">
	                                       <input type="text" class="form-control" id="name" name="user_name" value="{{$profile->first_name}} {{$profile->last_name}}">
	                                    </div>
	                                 </div>
	                                 <div class="form-group row">
	                                    <label for="inputPassword" class="col-sm-3 col-form-label"><i>*</i> Email</label>
	                                    <div class="col-sm-9">
	                                       <input type="email" class="form-control" id="email" name="user_email" value="{{$profile->email}}">
	                                    </div>
	                                 </div>
	                              </div>
	                              <div class="col-md-6">
	                                 <h3>Billing Address</h3>
	                                 <div class="form-group row">
	                                    <label for="staticEmail" class="col-sm-3 col-form-label">*Address</label>
	                                    <div class="col-sm-9">
	                                       <input type="text" class="form-control" id="user_address" name="user_address" value="{{$profile->address}}">
	                                    </div>
	                                 </div>
	                                 <div class="form-group row">
	                                    <label for="staticEmail" class="col-sm-3 col-form-label">*City</label>
	                                    <div class="col-sm-9">
	                                       <input type="text" class="form-control" id="user_city" value="{{$profile->city_state}}" name="user_city">
	                                    </div>
	                                 </div>
	                                 <div class="form-group row">
	                                    <label for="staticEmail" class="col-sm-3 col-form-label">*State/Zip</label>
	                                    <div class="col-sm-9 two-col">
	                                       <select class="form-control" name="user_state">
	                                          <option>--Select State -- </option>
	                                          <option value="" selected>Punjab</option>
	                                       </select>
	                                       <input type="text" class="form-control" placeholder="Zip" id="zip" name="user_zip" value="{{$profile->zip}}">
	                                    </div>
	                                 </div>
	                                 <div class="form-group row">
	                                    <label for="staticEmail" class="col-sm-3 col-form-label">*Country</label>
	                                    <div class="col-sm-9">
	                                       <select class="form-control" name="user_country">
	                                          <option>United State</option>
	                                          <option>India</option>
	                                       </select>
	                                    </div>
	                                 </div>
	                                 <h3>Payment Method</h3>
	                                 <div class="form-group row method">
	                                    <label for="staticEmail" class="col-sm-3 col-form-label">*Name on Card</label>
	                                    <div class="col-sm-9">
	                                       <div class="input-group mb-3">
	                                          <input type="text" class="form-control" placeholder="Name on Card" name="card_name" value="{{$profile->first_name}} {{$profile->last_name}}">
	                                          <div class="input-group-prepend">
	                                             <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock" aria-hidden="true"></i>
	                                             </span>
	                                          </div>
	                                       </div>
	                                    </div>
	                                 </div>
	                                 <div class="form-group row method">
	                                    <label for="staticEmail" class="col-sm-3 col-form-label">*Card Number</label>
	                                    <div class="col-sm-9">
	                                       <div class="input-group mb-3">
	                                          <input type="text" class="form-control" placeholder="Card Number" name="card_number">
	                                          <div class="input-group-prepend">
	                                             <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock" aria-hidden="true"></i>
	                                             </span>
	                                          </div>
	                                       </div>
	                                    </div>
	                                 </div>
	                                 <div class="form-group row">
	                                    <label for="staticEmail" class="col-sm-3 col-form-label">*Expiration/CVC</label>
	                                    <div class="col-sm-9 three_col">
	                                       <select class="form-control" name="card_expiry_month">
	                                       	@for($i = 1; $i<12;$i++)
	                                          <option value="{{$i}}">{{$i}}</option>
	                                        @endfor
	                                       </select>
	                                       <select class="form-control" name="card_expiry_year">
	                                       	@for($i = 2020; $i<2050;$i++)
	                                          <option value="{{$i}}">{{$i}}</option>
	                                      	@endfor
	                                       </select>
	                                       <div class="input-group mb-3">
	                                          <input type="text" class="form-control" placeholder="CVC" name="card_security_code">
	                                          <div class="input-group-prepend">
	                                             <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock" aria-hidden="true"></i>
	                                             </span>
	                                          </div>
	                                       </div>
	                                    </div>
	                                 </div>
	                                 <div class="spc_right">
	                                    <a type="submit" class="btn btn-primary" id="bookingSubmit">Submit Payment</a>
	                                 </div>
	                              </div>
	                           </div>
	                        </div>
	                     </div>
	                  </div>
	               </div>
	            </form>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- End Team section-->
@section('content')