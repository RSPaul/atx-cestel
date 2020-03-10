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
      </div>
   </div>
</section>
<!-- End Team section-->
@endsection