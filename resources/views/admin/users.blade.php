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
      <h6 class="m-0 font-weight-bold text-primary" style="text-transform: capitalize;">{{$type}}</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>City/State</th>
              <th>Zip</th>
              <th>Action</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>City/State</th>
              <th>Zip</th>
              <th>Action</th>
            </tr>
          </tfoot>
          <tbody>
          	@foreach($users as $user)
            <tr>
              <td>{{$user->first_name}}</td>
              <td>{{$user->last_name}}</td>
              <td>{{$user->email}}</td>
              <td>{{$user->phone}}</td>
              <td>{{($user->user_type == 'laundress' && $user->city_State == '') ? 'NA' :  $user->city_State}}</td>
              <td>{{$user->zip}}</td>
              <td> 
                @if($user->status == 0) 
                  <a href="javascript:void(0);" data-id="{{$user->id}}" class="btn btn-danger btn-circle btn-sm verify-user" title="Not Verified"> <i class="fas fa-exclamation-triangle" ></i> </a> 
                  <a class="btn btn-success btn-circle btn-sm" href="/admin/user/{{$user->id}}" title="View Details"> <i class="fas fa-eye"></i>
                  </a>
                @else 
                  <a class="btn btn-success btn-circle btn-sm" disabled title="Verified"> <i class="fas fa-check"></i>
                  </a>
                  <a class="btn btn-success btn-circle btn-sm" href="/admin/user/{{$user->id}}" title="View Details"> <i class="fas fa-eye"></i>
                  </a>                  
                @endif  
              </td>
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