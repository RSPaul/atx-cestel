@extends('layouts.main')
@section('content')
<section class="heading_top">
   <div class="container">
      <h2>Be part of the Cesta Team</h2>
      <div class="rad_btn"><a href="/login"> ALREADY PART OF THE TEAM</a></div>
   </div>
</section>
<!-- Start Team section-->
<section class="bepart_bx">
   <div class="container">
      @if(Session::has('error'))
      <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">
         {{ Session::get('error') }}
      </p>
      @endif
      @if(Session::has('success'))
      <p class="alert {{ Session::get('alert-class', 'alert-success') }}">
         {{ Session::get('success') }}
      </p>
      @endif
      <form name="be_part_form" action="{{ route('partTeam') }}" method="POST" id="bePartForm"  data-toggle="validator" role="form">
         @csrf
         <div class="row ">
            <h2>Tell us more about you</h2>
            <div class="border_bx">
               <div class="col-md-6">
                  <div class="form-group">
                     <label>First Name</label>
                     <input type="text" name="first_name" class="form-control" required  data-error="First name is required."/>
                    	<div class="help-block with-errors"></div>
                  </div>
                  <div class="form-group">
                     <div class="col">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required  data-error="That email address is invalid."/>
                        <div class="help-block with-errors"></div>
                     </div>
                  </div>
                  <div class="form-group">
                     <div class="col">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" data-error="Password is required." required/>
                        <div class="help-block with-errors"></div>
                     </div>
                  </div>
                  <div class="form-group">
                     <div class="col">
                        <label>Living Zip Code</label>
                        <input type="text" name="zip" class="form-control" data-error="Zip code is required." required />
                        <div class="help-block with-errors"></div>
                     </div>
                  </div>
                  <div class="form-group">
                     <div class="col">
                        <label>Phone Number </label>
                        <input type="tel" required name="phone" class="form-control" data-error="This phone no. is invalid." onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                        <div class="help-block with-errors"></div>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Last Name</label>
                     <input type="text" name="last_name" required class="form-control" data-error="Last name is required."/>
                     <div class="help-block with-errors"></div>
                  </div>
                  <div class="form-group">
                     <div class="col">
                        <label>Primary Language</label>
                        <div class="checklist">
                           <label><input type="checkbox" value="English" name="language[primary][]"/> English</label>
                           <label><input type="checkbox" value="Spanish" name="language[primary][]"/> Spanish</label>
                           <label><input type="checkbox" value="Other" name="language[primary][]"/> Other</label>
                        </div>
                        <div class="help-block with-errors"></div>
                     </div>
                     <div class="col">
                        <label>Secndary Language</label>
                        <div class="checklist">
                           <label><input type="checkbox" value="English" name="language[secondary][]"/> English</label>
                           <label><input type="checkbox" value="Spanish" name="language[secondary][]"/> Spanish</label>
                           <label><input type="checkbox" value="German" name="language[secondary][]"/> German</label>
                           <label><input type="checkbox" value="French" name="language[secondary][]"/> French</label>
                           <label><input type="checkbox" value="Other" name="language[secondary][]"/> Other</label>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row ">
            <h2>When are you available?</h2>
            <div class="border_bx">
               <div class="col-md-6">
                  <div class="form-group">
                     <div class="col-1">
                        <label>Monday</label>
                        <select name="available[monday_from][]" class="time" required data-error="Select your time availability.">
                           <option value="">Choose Time</option>
                           <option value="7:00 AM">7:00 AM</option>
                           <option value="8:00 AM">8:00 AM</option>
                        </select>
                        <select name="available[monday_to][]" class="time" required data-error="Select your time availability.">
                           <option value="">Choose Time</option>
                           <option value="5:00 PM">5:00 PM</option>
                           <option value="6:00 AM">6:00 AM</option>
                        </select>
                     	<div class="help-block with-errors"></div>
                     </div>
                  </div>
                  <div class="form-group">
                     <div class="col-1">
                        <label>TUESDAY</label>
                        <select name="available[tuesday_from][]" class="time" required data-error="Select your time availability.">
                           <option value="">Choose Time</option>
                           <option value="7:00 AM">7:00 AM</option>
                           <option value="8:00 AM">8:00 AM</option>
                        </select>
                        <select name="available[tuesday_to][]" class="time" required data-error="Select your time availability.">
                           <option value="">Choose Time</option>
                           <option value="5:00 PM">5:00 PM</option>
                           <option value="6:00 AM">6:00 AM</option>
                        </select>
                        <div class="help-block with-errors"></div>
                     </div>
                  </div>
                  <div class="form-group">
                     <div class="col-1">
                        <label>WEDNESDAY</label>
                        <select name="available[wednesday_from][]" class="time" required data-error="Select your time availability.">
                           <option value="">Choose Time</option>
                           <option value="7:00 AM">7:00 AM</option>
                           <option value="8:00 AM">8:00 AM</option>
                        </select>
                        <select name="available[wednesday_to][]" class="time" required data-error="Select your time availability.">
                           <option value="">Choose Time</option>
                           <option value="5:00 PM">5:00 PM</option>
                           <option value="6:00 AM">6:00 AM</option>
                        </select>
                        <div class="help-block with-errors"></div>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <div class="col-1">
                        <label>THURSDAY</label>
                        <select name="available[thursday_from][]" class="time" required data-error="Select your time availability.">
                           <option value="">Choose Time</option>
                           <option value="7:00 AM">7:00 AM</option>
                           <option value="8:00 AM">8:00 AM</option>
                        </select>
                        <select name="available[thursday_to][]" class="time" required data-error="Select your time availability.">
                           <option value="">Choose Time</option>
                           <option value="5:00 PM">5:00 PM</option>
                           <option value="6:00 AM">6:00 AM</option>
                        </select>
                        <div class="help-block with-errors"></div>
                     </div>
                  </div>
                  <div class="form-group">
                     <div class="col-1">
                        <label>FRIDAY</label>
                        <select name="available[friday_from][]" class="time" required data-error="Select your time availability.">
                           <option value="">Choose Time</option>
                           <option value="7:00 AM">7:00 AM</option>
                           <option value="8:00 AM">8:00 AM</option>
                        </select>
                        <select name="available[friday_to][]"class="time" required data-error="Select your time availability.">
                           <option value="">Choose Time</option>
                           <option value="5:00 PM">5:00 PM</option>
                           <option value="6:00 AM">6:00 AM</option>
                        </select>
                        <div class="help-block with-errors"></div>
                     </div>
                  </div>
                  <div class="form-group">
                     <div class="col-1">
                        <label>SATURDAY</label>
                        <select name="available[saturday_from][]" class="time" required data-error="Select your time availability.">
                           <option value="">Choose Time</option>
                           <option value="7:00 AM">7:00 AM</option>
                           <option value="8:00 AM">8:00 AM</option>
                        </select>
                        <select name="available[saturday_to][]" class="time" required data-error="Select your time availability.">
                           <option value="">Choose Time</option>
                           <option value="5:00 PM">5:00 PM</option>
                           <option value="6:00 AM">6:00 AM</option>
                        </select>
                        <div class="help-block with-errors"></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row ">
            <h2>A few more questions?</h2>
            <div class="border_bx">
               <div class="col-md-12">
                  <div class="form-group">
                     <label>TELL ME LITTLE ABOUT YOURSELF?</label>
                     <input type="text" name="more_questions[about_yourself][]" class="form-control"/>
                  </div>
                  <div class="form-group">
                     <label>WHAT MADE YOU APPLY FOR THIS POSITION?</label>
                     <input type="text" name="more_questions[apply_position][]" class="form-control"/>
                  </div>
                  <div class="form-group">
                     <label>ARE YOU COMFORTABLE GOING INTO A CUSTOMER HOME?</label>
                     <input type="text" name="more_questions[customer_home][]" class="form-control"/>
                  </div>
                  <div class="form-group">
                     <label>SINCE THIS POSITION REQUIRES YOU TO COMMUTE, DO YOU HAVE A RELIABLE MEANS OF TRANSPORTATION?</label>
                     <input type="text" name="more_questions[means_transportation][]" class="form-control"/>
                  </div>
                  <div class="form-group">
                     <label>CUSTOMER SERVICE IS HUGE IN THIS ROLE, PLEASE DESCRIBE A TIME THAT YOU HAD TO EXPERIENCE A CUSTOMER COMPLAINT, HOW DID YOU RESOLVE THE ISSUE?</label>
                     <input type="text" name="more_questions[customer_service][]" class="form-control"/>
                  </div>
               </div>
            </div>
         </div>
         <div class="text-center btn-rw">	
            <button type="submit" class="btn btn-primary">SUBMIT INFORMATION</button>	
         </div>
      </form>
   </div>
</section>
<!-- End Team section-->
@endsection