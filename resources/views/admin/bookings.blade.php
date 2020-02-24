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
      <h6 class="m-0 font-weight-bold text-primary" style="text-transform: capitalize;">Bookings</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Service Type</th>
              <th>Service Categories</th>
              <th>Service Day</th>
              <th>Service Package</th>
              <th>Service Amount</th>
              <th>Service Description</th>
              <th>Action</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>Service Type</th>
              <th>Service Categories</th>
              <th>Service Day</th>
              <th>Service Package</th>
              <th>Service Amount</th>
              <th>Service Description</th>
              <th>Action</th>
            </tr>
          </tfoot>
          <tbody>
          	@foreach($all_bookings as $booking)
            <?php 
              $categ = unserialize($booking->service_categories);
              $categ = implode(', ', $categ)
            ?>
            <tr>
              <td>{{$booking->service_type}}</td>
              <td><?php echo $categ; ?></td>
              <td>{{$booking->service_day}}</td>
              <td>{{$booking->service_package}}</td>
              <td>{{$booking->service_amount}}</td>
              <td>{{$booking->service_description}}</td>
              <td><a href="/admin/bookings/{{$booking->id}}">View</a></td>
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