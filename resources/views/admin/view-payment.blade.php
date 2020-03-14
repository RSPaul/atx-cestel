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
              <th>Service Type</th>
              <th>Service Date</th>
              <th>Service Time</th>
              <th>Service Package</th>
              <th>Service Tax</th>
              <th>Amount</th>
              <th>Provider Share</th>
              <th>Admin Share</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>Service Type</th>
              <th>Service Date</th>
              <th>Service Time</th>
              <th>Service Package</th>
              <th>Service Tax</th>
              <th>Amount</th>
              <th>Provider Share</th>
              <th>Admin Share</th>
            </tr>
          </tfoot>
          <tbody>
          	@foreach($bookings as $booking)
            <tr>
              <td>{{$booking->service_type}}</td>
              <td>{{$booking->service_day}}</td>
              <td>{{$booking->service_time}}</td>  
              <td>{{$booking->service_package}}</td>
              <td>{{round($booking->service_tax, 2)}}</td>
              <td>{{round($booking->service_amount, 2)}}</td>
              <td>{{round($booking->service_amount * 90 / 100, 2)}}</td>
              <td>{{round($booking->service_amount * 10 / 100, 2)}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @if($reqs->status == "paid")
          <button class="btn btn-primary" disabled>Paid - ${{$reqs->amount}}</button>
        @else
          <button class="btn btn-primary" id="confirmPayment" data-id="{{$reqs->id}}" data-amount="{{$reqs->amount}}">Confirm Payment - ${{$reqs->amount}}</button>
        @endif
      </div>
    </div>
  </div>

</div>
<!-- /.container-fluid -->

@endsection