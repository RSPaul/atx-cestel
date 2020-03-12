@extends('layouts.admin')
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <!-- <h1 class="h3 mb-2 text-gray-800">Users List</h1>
  <p class="mb-4">This is users list.</p> -->

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary" style="text-transform: capitalize;">Booking Details</h6>
    </div>
    <div class="card-body">
      <div class="card col-md-12">
        <div class="card-header">
          <span>Booking Details</span> <button onclick="return window.history.back();" style="float: right;" class="btn btn-sm btn-primary pull-right">Back</button>
        </div>
        <div class="card-body">
            <p>Date/Time: <b>{{$booking->service_day}} {{$booking->service_time}}</b></p>
            <p>Service Type: <b>{{$booking->service_type}}</b></p>
            <p>Service Categories: <b>{{implode(',',unserialize($booking->service_categories))}}</b></p>
            <p>Package: <b>{{$booking->service_package}}</b></p>
            <p>Service Tax: <b>{{$booking->service_tax}}</b></p>
            <p>Price: <b>{{$booking->service_amount}}</b></p>
            <p>Notes: <b>{{$booking->service_description}}</b></p>
            <p>Job Details: <b>{{$booking->service_job_details}}</b></p>
            <p>Job Status: 
              @if($booking->status == 'new')
                <button class="btn btn-sm btn-info">New Booking</button>
              @elseif($booking->status == 'confirmed')
                <button class="btn btn-sm btn-warning">Confirmed from laundress</button>
              @elseif($booking->status == 'completed')
                <button class="btn btn-sm btn-success">Completed by laundress</button>
              @elseif($booking->status == 'paid')
                <button class="btn btn-sm btn-dark">Completed</button>
              @elseif($booking->status == 'declined')
                <button class="btn btn-sm btn-danger">Booking Canceled</button>
              @else
                <button class="btn btn-sm btn-light">Past Booking</button>
              @endif
            </p>
        </div>
      </div>
      <p>&nbsp;</p>
      <div class="row col-md-12">
        <div class="card col-md-6">
          <div class="card-header">
            Customer Details
          </div>
          <div class="card-body">
            <p>Name: <b>{{$booking->cfn}} {{$booking->cln}}</b></p>
            <p>Email: <b>{{$booking->ce}}</b></p>
            <p>Phone: <b>{{$booking->cp}}</b></p>
            <p>Address: <b>{{$booking->ca}}</b></p>
          </div>
        </div>
        <div class="card col-md-6">
          <div class="card-header">
            Laundress Details
          </div>
          <div class="card-body">
            <p>Name: <b>{{$booking->pfn}} {{$booking->pln}}</b></p>
            <p>Email: <b>{{$booking->pe}}</b></p>
            <p>Phone: <b>{{$booking->pp}}</b></p>
            <p>Address: <b>{{$booking->pa}}</b></p>
          </div>            
        </div>
      </div>
    </div>
  </div>

</div>
<!-- /.container-fluid -->

@endsection