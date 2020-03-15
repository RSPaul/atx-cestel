@extends('layouts.main')
@section('content')
<section class="heading_top">
   <!-- <div class="container">
      <h2>Be part of the Cesta Team</h2>
      <div class="rad_btn"><a href="/login"> ALREADY PART OF THE TEAM</a></div>
   </div> -->
</section>
<!-- Start Team section-->
<section class="bepart_bx">
   <div class="container">
      @if(Session::has('error'))
      <p class="alert {{ Session::get('alert-class', 'alert-danger text-center') }}">
         {{ Session::get('error') }}
      </p>
      @endif
      @if(Session::has('success'))
      <p class="alert {{ Session::get('alert-class', 'alert-success text-center') }}">
         {{ Session::get('success') }}
      </p>
      @endif
      <div class="row">
         <h3 class="text-center">Booking Completed.</h3>
         <span class="details_schedule">Provider First Name: <b>{{ $bookings[0]->first_name }}</b></span><br /><br />
         <span class="details_schedule">Provider Last Name: <b>{{ $bookings[0]->last_name }}</b></span><br /><br />
         <span class="details_schedule">Service Address: <b>{{ $bookings[0]->service_address }}</b></span><br /><br />
         <span class="details_schedule">Provider State: <b>{{ $bookings[0]->city_state }}</b></span><br /><br />
         <span class="details_schedule">Service Type: <b>{{ $bookings[0]->service_type }}</b></span><br /><br />
         <span class="details_schedule">Service Categories: {{implode(',',unserialize($bookings[0]->service_categories))}}</span><br /><br />
         <span class="details_schedule">Service Quantity:
         <?php foreach(unserialize($bookings[0]->service_quantity) as $key => $value){
          if($value != ''){
               echo "<br />" . $key." :".$value."<br />";
            }
         }
         ?>
         </span><br /><br />
         <span class="details_schedule">Service Day: <b>{{ $bookings[0]->service_day }}</b></span><br /><br />
         <span class="details_schedule">Service Time: <b>{{ $bookings[0]->service_time }}</b></span><br /><br />
         <span class="details_schedule">Service Laundress: <b>{{ $bookings[0]->service_laundress }}</b></span><br /><br />
         <span class="details_schedule">Service Package: <b>{{ $bookings[0]->service_package }}</b></span><br /><br />
         <span class="details_schedule">Service Tax: $<b>{{ $bookings[0]->service_tax }}</b></span><br /><br />
         <span class="details_schedule">Service Amount: $<b>{{ $bookings[0]->service_amount -  $bookings[0]->service_tax }} </b></span><br /><br />
         <span class="details_schedule">Service Job Details: <b>{{ $bookings[0]->service_job_details }}</b></span><br /><br />
         <span class="details_schedule">Service Folding Details: <b>{{ $bookings[0]->service_folding_details }}</b></span><br /><br />
         <span class="details_schedule">Service Hanging Details: <b>{{ $bookings[0]->service_hanging_details }}</b></span><br /><br />
         <span class="details_schedule">Service Washing Details: <b>{{ $bookings[0]->service_washing_details }}</b></span><br /><br />
         <span class="details_schedule">Total Amount: $<b>{{ $bookings[0]->service_amount }} </b></span><br /><br />
      </div>
   </div>
</section>
<!-- End Team section-->
@endsection