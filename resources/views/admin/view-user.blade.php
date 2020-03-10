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
      <h6 class="m-0 font-weight-bold text-primary" style="text-transform: capitalize;">User Details</h6>
    </div>
    <div class="card-body">
      <div class="card col-md-12">
        <div class="card-header">
          <span>User Details</span> <button onclick="return window.history.back();" style="float: right;" class="btn btn-sm btn-primary pull-right">Back</button>
        </div>
        <div class="card-body">
            <p>First Name: <b>{{$user->first_name}}</b></p>
            <p>Last Name: <b>{{$user->last_name}}</b></p>
            <p>Email: <b>{{$user->email}}</b></p>
            <p>User Type: <b>{{$user->user_type}}</b></p>

            <p>Address: <b>{{$user->address}}</b></p>
            <p>City/State: <b>{{$user->city_state}}</b></p>
            <p>Zip: <b>{{$user->zip}}</b></p>
            <p>Phone: <b>{{$user->phone}}</b></p>
        </div>
      </div>
      <p>&nbsp;</p>
      
    </div>
  </div>

</div>
<!-- /.container-fluid -->

@endsection