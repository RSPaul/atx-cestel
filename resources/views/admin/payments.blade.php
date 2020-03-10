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
      <h6 class="m-0 font-weight-bold text-primary" style="text-transform: capitalize;">Payments</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Provider Name</th>
              <th>Provide Email</th>
              <th>Provider Phone</th>
              <th>Amount</th>
              <th>Action</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>Provider Name</th>
              <th>Provide Email</th>
              <th>Provider Phone</th>
              <th>Amount</th>
              <th>Action</th>
            </tr>
          </tfoot>
          <tbody>
          	@foreach($payments as $payment)
            <tr>
              <td>{{$payment->first_name}} {{$payment->last_name}}</td>
              <td>{{$payment->email}}</td>
              <td>{{$payment->phone}}</td>
              <td>{{round($payment->amount, 2)}}</td>
              <td><a href="/admin/payment/{{$payment->id}}">View</a></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
<!-- /.container-fluid -->

@endsection