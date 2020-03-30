@extends('layouts.main')
@section('content')
<div class="ng-app" ng-app="laundressUserApp" ng-controller="laundressUserCtrl">
   <section class="heading_top">
      <div class="container">
         <div class="avatar_info">
            <div class="avatar profile-img">
               <img class="user-edit-img" src="{{asset('uploads/profiles')}}/{{$profile->profile_pic}}" alt="{{$profile->first_name}} {{$profile->last_name}}" onerror="this.onerror=null;this.src='{{asset("uploads/profiles/l60Hf.png")}}'"/>
            </div>
            <div class="avatar-info">
               <h3> @{{user.first_name}} @{{user.last_name}}</h3>
               <!-- <p>The Cleaning Company</p> -->
               <div class="line_bx">
                  <h2><span class="dotted"></span> My Account</h2>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- Start Team section-->
   <section class="bepart_bx">
      <div class="container">
         <div class="row ">
            <div class="alert alert-success text-center" ng-if="successMsg">@{{successMsg}}</div>
            <div class="alert alert-danger text-center" ng-if="errMsg">@{{errMsg}}</div>
            <div class="border_bx_acc">
               <div class="col-md-2">
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs nav-tabs-dropdown" role="tablist">
                     <li role="presentation" class="@if($tab_id == 'dashboard') active @endif"">
                        <a href="#dashboard" aria-controls="dashboard" role="tab" data-toggle="tab">
                           <span class="bor_rad">
                              <img src="{{asset('img/dashboard.png')}}" alt="dashboard"/>
                           </span> <b>Dashboard</b>
                        </a>
                     </li>
                     <li role="presentation"  class="@if($tab_id == 'profile') active @endif"">
                        <a href="#account" aria-controls="account" role="tab" data-toggle="tab">
                           <span class="bor_rad">
                              <img src="{{asset('img/account.png')}}" alt="account"/>
                           </span> <b>Account</b>
                        </a>
                     </li>
                     <li role="presentation"class="@if($tab_id == 'schedule') active @endif"">
                        <a href="#schedule" aria-controls="schedule" role="tab" data-toggle="tab">
                           <span class="bor_rad">
                              <img src="{{asset('img/schedule.png')}}" alt="schedule"/>
                           </span> <b>Schedule</b>
                        </a>
                     </li>
                     <li role="presentation" class="@if($tab_id == 'prevserv') active @endif"">
                        <a href="#prevserv" aria-controls="prevserv" role="tab" data-toggle="tab">
                           <span class="bor_rad">
                              <img src="{{asset('img/prev_service.png')}}" alt="prev_service"/>
                           </span> <b>Previous Service</b>
                        </a>
                     </li>
                     <li role="presentation" class="@if($tab_id == 'payments') active @endif"">
                        <a href="#payments" aria-controls="payments" role="tab" data-toggle="tab">
                           <span class="bor_rad">
                              <img src="{{asset('img/prev_service.png')}}" alt="Payments"/>
                           </span> <b>Payments</b>
                        </a>
                     </li>
                     <li role="presentation">
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                           <span class="bor_rad">
                              <img src="{{asset('img/logout.png')}}" alt="logout"/>
                           </span> <b>Logout</b>
                        </a>
                     </li>
                     <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                     </form>
                  </ul>
               </div>
               <div class="col-md-10">
                  <!-- Tab panes -->
                  <div class="tab-content">
                     <div role="tabpanel" class="tab-pane @if($tab_id == 'dashboard') active @endif" id="dashboard">
                        <div class="btn_rw">
                           <a href="javascript:void(0);" class="btn btn-wht" ng-click="showTodayBookings = true" ng-class="{'active': showTodayBookings}">Today's Schedule</a>
                           <a href="javascript:void(0);" class="btn btn-wht" ng-click="showTodayBookings = false" ng-class="{'active': !showTodayBookings}">Next 7 Days</a>
                        </div>
                        <div class="row table_bx">
                           <!-- today's bookings -->
                           <div class="col-md-9" ng-if="showTodayBookings">
                              <table class="table">
                                 <tr>
                                    <th>Time</th>
                                    <th>Date</th>
                                    <th>Payment Type</th>
                                    <th>Services</th>
                                    <th>Location</th>
                                    <th>Customer</th>
                                 </tr>
                                 <tr ng-repeat="booking in bookings.today">
                                    <td><b>@{{booking.service_time}}</b></td>
                                    <td><b>@{{booking.service_day}}</b></td>
                                    <td><b>@{{booking.service_payment_type}}</b></td>
                                    <td><b>@{{booking.service_type}}</b></td>
                                    <td><b>@{{booking.city_state}}</b> @{{booking.service_address}}
                                    </td>
                                    <td><b>@{{booking.first_name}} @{{booking.last_name}}</b> <a href="javascript:void(0);" ng-click="viewBooking(booking)"><i class="fa fa-chevron-right"></a></td>
                                 </tr>
                                 <tr ng-if="!bookings.today.length">
                                    <td class="full-width text-center">No bookings available.</td>
                                 </tr>
                              </table>
                           </div>
                           <!-- next week bookings -->
                           <div class="col-md-9" ng-if="!showTodayBookings">
                              <table class="table">
                                 <tr>
                                    <th>Time</th>
                                    <th>Date</th>
                                    <th>Payment Type</th>
                                    <th>Services</th>
                                    <th>Location</th>
                                    <th>Customer</th>
                                 </tr>
                                 <tr ng-repeat="booking in bookings.next_week">
                                    <td><b>@{{booking.service_time}}</b></td>
                                    <td><b>@{{booking.service_day}}</b></td>
                                    <td><b>@{{booking.service_payment_type}}</b></td>
                                    <td><b>@{{booking.service_type}}</b></td>
                                    <td><b>@{{booking.city_state}}</b> @{{booking.service_address}}
                                    </td>
                                    <td><b>@{{booking.first_name}} @{{booking.last_name}}</b> <a href="javascript:void(0);" ng-click="viewBooking(booking)"><i class="fa fa-chevron-right"></a></td>
                                 </tr>
                                 <tr ng-if="!bookings.next_week.length">
                                    <td class="full-width text-center">No bookings available.</td>
                                 </tr>
                              </table>
                           </div>
                           <div class="col-md-3">
                              <table class="table">
                                 <tr>
                                    <th colspan="2">Estimated Earnings</th>
                                 </tr>
                                 <tr>
                                    <td colspan="2">@{{earningsData.weekStart}} >>> @{{earningsData.weekEnd}}</td>
                                 </tr>
                                 <tr>
                                    <td colspan="2">
                                       <table>
                                          <tr ng-repeat="earnings in earningsData.weekEarnings">
                                             <td><span class="line line-green"></span> @{{earnings.name}}</td>
                                             <td>@{{earnings.amount | currency}}</td>
                                          </tr>
                                          <!-- <tr>
                                             <td><span class="line line-sky"></span> Ironing</td>
                                             <td>$450</td>
                                          </tr>
                                          <tr>
                                             <td><span class="line line-purple"></span> Bed Making</td>
                                             <td>$450</td>
                                          </tr>
                                          <tr>
                                             <td><span class="line line-orange"></span> Organizing</td>
                                             <td>$450</td>
                                          </tr>
                                          <tr>
                                             <td><span class="line line-yellow"></span> Packing</td>
                                             <td>$450</td>
                                          </tr> -->
                                       </table>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>Weekly Revenue</td>
                                    <td>@{{earningsData.totalEarning | currency}}</td>
                                 </tr>
                              </table>
                           </div>
                        </div>
                     </div>
                     <div role="tabpanel" class="tab-pane @if($tab_id == 'profile') active @endif" id="account">
   	                  <form autocomplete="off" method="POST" accept="" ng-submit="updateProfile()" novalidate>
   	                     @csrf
   	                     <div role="tabpanel" class="tab-pane active" id="account">
   	                     	<div class="col-md-12 chef-account-data-row">
                                   <div class="row">
                                       <div class="col-md-12 text-center">
                                           <a href="#" class="profile-img" data-toggle="modal" data-target="#edit-photo" class="active" ><img class="user-edit-img" alt="user avtar" src="{{asset('uploads/profiles')}}/{{$profile->profile_pic}}" onerror="this.onerror=null;this.src='{{asset("uploads/profiles/l60Hf.png")}}'"  /></a><br/>
                                           <b class="" data-toggle="modal" data-target="#edit-photo" class="active">Edit Photo</b>
                                       </div>
                                   </div>
                               </div>
   	                        <div class="form-group">
   	                           <label>First Name</label>
   	                           <input type="text" name="first_name" class="form-control" ng-model="user.first_name"  required/>
   	                        </div>
   	                        <div class="form-group">
   	                           <label>Last Name</label>
   	                           <input type="text" name="last_name" class="form-control" ng-model="user.last_name" required/>
   	                        </div>
   	                        <div class="form-group">
   	                           <label>Email</label>
   	                           <input type="email" name="email" class="form-control"  ng-model="user.email" disabled readonly required/>
   	                        </div>
   	                        <div class="form-group">
   	                           <label>Phone Number</label>
   	                           <input type="text" name="phone" class="form-control" ng-model="user.phone" maxlength="11" required onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
   	                        </div>
                              <div class="form-group">
                                 <label>Address</label>
                                 <input type="text" name="address" class="form-control" ng-model="user.address" required/>
                              </div>
                              <div class="form-group">
                                 <label>City/State</label>
                                 <input type="text" name="city_state" class="form-control" ng-model="user.city_state" required/>
                              </div>
                              <div class="form-group">
                                 <label>Zip</label>
                                 <input type="text" name="zip" class="form-control" ng-model="user.zip" maxlength="6" required/>
                              </div>
   	                        <div class="form-group">
   	                           <label>Current Password</label>
   	                           <input type="password" name="password" class="form-control" ng-model="user.current_password"/>
   	                        </div>
   	                        <div class="form-group">
   	                           <label>New Password</label>
   	                           <input type="password" name="new" class="form-control" ng-model="user.password"/>
   	                        </div>
   	                        <div class="form-group">
   	                           <label>Confirmed New Password</label>
   	                           <input type="password" name="confirm" class="form-control" ng-model="user.confirm_password"/>
   	                        </div>
   	                        <div class=" btn-rw">	
   	                           <input type="submit" class="btn btn-lg btn-primary" ng-disabled="!user.first_name || !user.last_name || !user.email || !user.phone || !user.address || !user.city_state" value="Update">
   	                           <!-- <a href="#" class="btn btn-primary">Fill Out Forms</a>
   	                           <a href="#" class="btn btn-primary">Upload Forms</a>
                                 <br> -->
                                 <!-- alert messages -->
                                 <!-- <div class="alert alert-success text-center" ng-show="successMessage" ng-bind="successMessage"></div>
                                 <div class="alert alert-danger text-center" ng-show="errorMessage" ng-bind="errorMessage"></div> -->
   	                        </div>
   	                     </div>
   	                  </form>
   	              </div>
                     <div role="tabpanel" class="tab-pane @if($tab_id == 'schedule') active @endif" id="schedule">
                        <div class="btn_rw">
                           <a href="javascript:void(0)" ng-click="showTodBookings = false; showTomBookings = true; showWeekBookings = true; showMonthBookings = true;showCustomBookings= true" ng-class="{'active': !showTodBookings}" class="btn btn-wht" id="today">Today</a>
                           <a href="javascript:void(0)" ng-click="showTomBookings = false; showTodBookings = true; showWeekBookings = true; showMonthBookings = true; showCustomBookings= true" ng-class="{'active': !showTomBookings}" class="btn btn-wht">Tommorrow</a>
                           <a href="javascript:void(0)" ng-click="showWeekBookings = false; showTomBookings = true; showTodBookings = true; showMonthBookings = true;showCustomBookings= true" ng-class="{'active': !showWeekBookings}" class="btn btn-wht" id="thisweek">This Week</a>
                           <a href="javascript:void(0)" ng-click="showMonthBookings = false; showTomBookings = true; showWeekBookings = true; showTodBookings = true;showCustomBookings= true" ng-class="{'active': !showMonthBookings}" class="btn btn-wht">This Month</a>
                           <div class="date">
                              <div class="col-date">
                                 <div class='input-group date' id='datetimepicker1'>
                                    <input type='text' id="from_date_picker" ng-model="custom.from_date" name="from_date" ng-change="customresultfun()" class="form-control" placeholder="From Date" />
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                 </div>
                              </div>
                              <span class="to">to</span>
                              <div class="col-date">
                                 <div class='input-group date' id='datetimepicker2'>
                                    <input type='text' id="to_date_picker" ng-disabled="!custom.from_date" ng-change="showCustomBookings = false;showTodBookings = true;showTomBookings = true; showWeekBookings = true; showMonthBookings = true; customresultfun()" ng-model="custom.to_date" name="to_date" class="form-control" placeholder="To Date" />
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                 </div>
                              </div>
                             <!--  <input type="button" ng-disabled="!custom.to_date && !custom.from_date" ng-click="showCustomBookings = false;showTodBookings = true;showTomBookings = true; showWeekBookings = true; showMonthBookings = true; customresultfun()" class="btn btn-default" value="Submit"> -->
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                              <div id="showinfo">
                                <?php 
                                 $currentdate = date('m/d/Y');
                                 $tomDate = date( "m/d/Y", strtotime( "$currentdate +1 day" ) );
                                 $WeekDate = Date('m/d/Y', StrToTime("Next Sunday")); 
                                 $lastDayThisMonth = date("Y-m-t");
                                 ?>
                                 <h3 ng-if="!showTodBookings"><?php echo date('M d, Y'); ?></h3>
                                 <h3 ng-if="!showTomBookings"><?php echo date("M d, Y", strtotime($tomDate)); ?></h3>
                                 <h3 ng-if="!showWeekBookings"><?php echo date('M d, Y'); ?> - <?php echo date("M d, Y", strtotime($WeekDate)); ?></h3>
                                 <h3 ng-if="!showMonthBookings"><?php echo date('M d, Y'); ?> - <?php echo date("M d, Y", strtotime($lastDayThisMonth)); ?></h3>
                                 <div class="group_list">
                                    <table class="border_table">
                                       <tr class="booking_heading"> 
                                          <td>Service Time</td>
                                          <td>Service Date</td>
                                          <td>Service Type</td>
                                          <td>Payment Type</td>
                                          <td>Service Package</td>
                                          <td>Service Amount</td>
                                          <td>Action</td>
                                       </tr>
                                       <tr ng-repeat="booking in schedulebookings.today_bookings" ng-if="!showTodBookings">
                                          <td>@{{booking.service_time}}</td>
                                          <td>@{{booking.service_day}}</td>
                                          <td>@{{booking.service_type}}</td>
                                          <td>@{{booking.service_payment_type}}</td>
                                          <td>@{{booking.service_package}}</td>
                                          <td>@{{(booking.service_amount - booking.service_tax ) | currency}}</td>
                                          <td><a href="javascript:void(0);" ng-click="viewBooking(booking)" title="View Booking"><i class="fa fa-eye"></i></a> <a href="javascript:void(0);" ng-click="cancelBooking(booking)" title="Decline Booking"><i class="fa fa-trash"></i> </a> </td>
                                       </tr>
                                       <tr ng-if="!schedulebookings.today_bookings.length && !showTodBookings">
                                          <td class="full-width text-center">No bookings available.</td>
                                       </tr>
                                       <tr ng-repeat="booking in schedulebookings.tom_bookings" ng-if="!showTomBookings">
                                          <td>@{{booking.service_time}}</td>
                                          <td>@{{booking.service_day}}</td>
                                          <td>@{{booking.service_type}}</td>
                                          <td>@{{booking.service_payment_type}}</td>
                                          <td>@{{booking.service_package}}</td>
                                          <td>@{{(booking.service_amount - booking.service_tax ) | currency}}</td>
                                          <td><a href="javascript:void(0);" ng-click="viewBooking(booking)" title="View Booking"><i class="fa fa-eye"></i></a> <a href="javascript:void(0);" ng-click="cancelBooking(booking)" title="Decline Booking"><i class="fa fa-trash"></i> </a> </td>
                                       </tr>
                                       <tr ng-if="!schedulebookings.tom_bookings.length && !showTomBookings">
                                          <td class="full-width text-center">No bookings available.</td>
                                       </tr>
                                       <tr ng-repeat="booking in schedulebookings.week_bookings" ng-if="!showWeekBookings">
                                          <td>@{{booking.service_time}}</td>
                                          <td>@{{booking.service_day}}</td>
                                          <td>@{{booking.service_type}}</td>
                                          <td>@{{booking.service_payment_type}}</td>
                                          <td>@{{booking.service_package}}</td>
                                          <td>@{{(booking.service_amount - booking.service_tax ) | currency}}</td>
                                          <td><a href="javascript:void(0);" ng-click="viewBooking(booking)" title="View Booking"><i class="fa fa-eye"></i></a> <a href="javascript:void(0);" ng-click="cancelBooking(booking)" title="Decline Booking"><i class="fa fa-trash"></i> </a> </td>
                                       </tr>
                                       <tr ng-if="!schedulebookings.week_bookings.length && !showWeekBookings">
                                          <td class="full-width text-center">No bookings available.</td>
                                       </tr>
                                       <tr ng-repeat="booking in schedulebookings.month_bookings" ng-if="!showMonthBookings">
                                          <td>@{{booking.service_time}}</td>
                                          <td>@{{booking.service_day}}</td>
                                          <td>@{{booking.service_type}}</td>
                                          <td>@{{booking.service_payment_type}}</td>
                                          <td>@{{booking.service_package}}</td>
                                          <td>@{{(booking.service_amount - booking.service_tax ) | currency}}</td>
                                          <td><a href="javascript:void(0);" ng-click="viewBooking(booking)" title="View Booking"><i class="fa fa-eye"></i></a> <a href="javascript:void(0);" ng-click="cancelBooking(booking)" title="Decline Booking"><i class="fa fa-trash"></i> </a> </td>
                                       </tr>
                                       <tr ng-if="!schedulebookings.month_bookings.length && !showMonthBookings">
                                          <td class="full-width text-center">No bookings available.</td>
                                       </tr>
                                       <tr ng-repeat="booking in customresultbookings" ng-if="!showCustomBookings">
                                          <td>@{{booking.service_time}}</td>
                                          <td>@{{booking.service_day}}</td>
                                          <td>@{{booking.service_type}}</td>
                                          <td>@{{booking.service_payment_type}}</td>
                                          <td>@{{booking.service_package}}</td>
                                          <td>@{{(booking.service_amount - booking.service_tax ) | currency}}</td>
                                          <td><a href="javascript:void(0);" ng-click="viewBooking(booking)" title="View Booking"><i class="fa fa-eye"></i></a> <a href="javascript:void(0);" ng-click="cancelBooking(booking)" title="Decline Booking"><i class="fa fa-trash"></i> </a> </td>
                                       </tr>
                                       <tr ng-if="!customresultbookings.length && !showCustomBookings">
                                          <td class="full-width text-center">No bookings available.</td>
                                       </tr>
                                    </table>
                                 </div>
                              </div>
                              <div id="cal_bx" class="group_list">
                                 <div class="mycal"></div>
                              </div>
                           </div>

                        </div>
                     </div>
                     <div role="tabpanel" class="tab-pane table_bx @if($tab_id == 'prevserv') active @endif" id="prevserv">
                        <div class="col-md-12">
                              <table class="table">
                                 <tr>
                                    <th>Service Time</th>
                                    <th>Service Date</th>
                                    <th>Payment Type</th>
                                    <th>Service Type</th>
                                    <th>Location</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                 </tr>
                                 <tr ng-repeat="booking in bookings.past_bookings">
                                    <td><b>@{{booking.service_time}}</b></td>
                                    <td><b>@{{booking.service_day}}</b></td>
                                    <td><b>@{{booking.service_payment_type}}</b></td>
                                    <td><b>@{{booking.service_type}}</b></td>
                                    <td><b>@{{booking.city_state}}</b> @{{booking.service_address}}
                                    </td>
                                    <td><b>@{{booking.first_name}} @{{booking.last_name}}</b> </td><td ng-if="booking.status == 'new'" ><b>Active</b> </td>
                                    <td ng-if="booking.status != 'new'" ><b>@{{booking.status}}</b></td>
                                    <td><a href="javascript:void(0);" ng-click="viewBooking(booking)" title="View Booking"><i class="fa fa-eye"></i></a></td>
                                 </tr>
                                 <tr ng-if="!bookings.past_bookings.length">
                                    <td class="full-width text-center">No bookings available.</td>
                                 </tr>
                              </table>
                           </div>
                     </div>
                     <div role="tabpanel" class="tab-pane @if($tab_id == 'payments') active @endif" id="payments">
                        <div class="btn_rw">
                           <a href="javascript:void(0);" class="btn btn-wht" ng-click="showBankAccount = true" ng-class="{'active': showBankAccount}">Account Details</a>
                           <a href="javascript:void(0);" class="btn btn-wht" ng-click="showBankAccount = false" ng-class="{'active': !showBankAccount}">Earnings</a>
                        </div>
                        <div class="row" ng-if="showBankAccount">
                           <div class="alert alert-danger" ng-if="!bank.bank_name || !bank.routing_number || !bank.account_number || !bank.year || !bank.day || !bank.month || !bank.line1 || !bank.line2 || !bank.phone || !bank.city || !bank.state || !bank.country || !bank.postal_code || !bank.mcc || !bank.url || !bank.id_number || !bank.ssn_last_4 || !bank.front || !bank.back">All Fields are required.</div>
                           <form ng-submit="updateBankAccount()" novalidate>
                              <div class="col-md-12">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Name</label>
                                       <input type="text" name="bank_name" ng-model="bank.bank_name" class="form-control" required>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Routing Number</label>
                                       <input type="text" name="routing_number" ng-model="bank.routing_number" class="form-control" required>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Account Number</label>
                                       <input type="text" name="account_number" ng-model="bank.account_number" class="form-control" required>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <label>DOB</label>
                                    <div class="form-group">
                                       <div class="col-md-4">
                                          <input type="text" name="day" ng-model="bank.day" class="form-control" required placeholder="DD">
                                       </div>
                                       <div class="col-md-4">
                                          <input type="text" name="month" ng-model="bank.month" class="form-control" required placeholder="MM">
                                       </div>
                                       <div class="col-md-4">
                                          <input type="text" name="year" ng-model="bank.year" class="form-control" required placeholder="YYYY">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Address Line 1</label>
                                       <input type="text" name="line1" ng-model="bank.line1" class="form-control" required>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Address Line 2</label>
                                       <input type="text" name="line2" ng-model="bank.line2" class="form-control" required>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Phone</label>
                                       <input type="text" name="phone" ng-model="bank.phone" class="form-control" required>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>City</label>
                                       <input type="text" name="city" ng-model="bank.city" class="form-control" required>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>State</label>
                                       <input type="text" name="state" ng-model="bank.state" class="form-control" required>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Country</label>
                                       <select name="country" ng-model="bank.country" class="form-control" required>
                                          <option value="">Select</option>
                                          <option value="US">Unites States</option>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Zip</label>
                                       <input type="text" name="postal_code" ng-model="bank.postal_code" class="form-control" required>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Industry</label>
                                       <select name="mcc" ng-model="bank.mcc" class="form-control" required>
                                          <option value="">Select</option>
                                          <option value="1520">General Contractors-Residential and Commercial</option>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Website</label>
                                       <input type="text" name="url" ng-model="bank.url" class="form-control" required>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>SSN</label>
                                       <input type="text" name="id_number"  ng-model="bank.id_number" class="form-control" required>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>SSN Last 4</label>
                                       <input type="text" name="ssn_last_4"  ng-model="bank.ssn_last_4" class="form-control" required>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Photo ID - Front</label>
                                       <input type="file" name="front_pic" class="form-control" required  ng-upload-change="fileNameChanged($event, 'front')">
                                       <input type="hidden" id="front" name="front" >
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Photo ID - Back</label>
                                       <input type="file" name="back_pic" class="form-control" required ng-upload-change="fileNameChanged($event, 'back')">
                                       <input type="hidden" id="back" name="back" >
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-12 text-center mt-4">
                                 <!-- <input type="submit" name="submit" value="Submit" class="btn btn-primary" > -->
                                 <input type="submit" name="submit" value="@{{accUpdateBtn}}" class="btn btn-primary" ng-disabled="!bank.bank_name || !bank.routing_number || !bank.account_number || !bank.year || !bank.day || !bank.month || !bank.line1 || !bank.line2 || !bank.phone || !bank.city || !bank.state || !bank.country || !bank.postal_code || !bank.mcc || !bank.url || !bank.id_number || !bank.ssn_last_4 || !bank.front || !bank.back">
                              </div>
                           </form>
                        </div>
                        <div class="row col-md-12" ng-if="!showBankAccount">
                           <div class="row table_bx col-md-12">
                              <div class="col-md-12" >
                                 <table class="table">
                                    <tr>
                                       <!-- <th>Time</th> -->
                                       <th>Booking Date</th>
                                       <th>Payment Type</th>
                                       <th>Services</th>
                                       <th>Total Amount</th>
                                       <th>Service Tax</th>
                                       <th>Your Share</th>
                                       <th>Admin Share</th>
                                       <th>Status</th>
                                       <th>Action</th>
                                    </tr>
                                    <tr ng-repeat="booking in payments">
                                       <!-- <td><b>@{{booking.service_time}}</b></td> -->
                                       <td><b>@{{booking.service_day}}</b></td>
                                       <td><b>@{{booking.service_payment_type}}</b></td>
                                       <td><b>@{{booking.service_type}}</b></td>
                                       <td><b>@{{booking.service_amount | currency}}
                                       </td>
                                       <td><b>@{{booking.service_tax | currency}}
                                       </td>
                                       <td><b>@{{((booking.service_amount - booking.service_tax ) * 90 / 100) | currency }}</td>
                                       <td><b>@{{((booking.service_amount - booking.service_tax ) * 10 / 100) | currency }}</td>
                                       <td ng-if="booking.payment_request === '0'">
                                          Not Requested
                                       </td>
                                       <td ng-if="booking.payment_request === '1'">
                                          Requested
                                       </td>
                                       <td ng-if="booking.payment_request === '2'">
                                          Completed
                                       </td>
                                       <td ng-if="booking.payment_request === '0'">
                                          Payment Pending
                                       </td>
                                       <td ng-if="booking.payment_request === '1'">
                                          Payment Pending
                                       </td>
                                       <td ng-if="booking.payment_request === '2'">
                                          Payment Completed
                                       </td>
                                    </tr>
                                 </table>
                                 <h5 ng-if="!bank.account_id">Add Account details to send request for Payment.</h5>
                                 <a href="javascript:void(0);" class="btn btn-primary" ng-click="requestPayment()" ng-if="totalPayment > 0 && bank.account_id">Request Payment $@{{totalPayment}}</a>
                                 <a href="javascript:void(0);" class="btn btn-primary" ng-if="totalPayment == 0 || !bank.account_id" disabled>Request Payment $@{{totalPayment}}</a>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div role="tabpanel" class="tab-pane" id="logout">...</div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- End Team section-->

   <!-- The Modal -->
   <div class="modal fade" id="viewSchedule" tabindex="-1" role="dialog" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered" role="document">
           <div class="modal-content">
               <div class="modal-header text-center">
               <h4 class="modal-title">Details</h4>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                   </button>
               </div>         
               <div class="modal-body">
                   <div class="row">
                        
                       <span class="details_schedule">Customer First Name: <b>@{{ schedule.first_name }}</b></span><br /><br />
                       <span class="details_schedule">Customer Last Name: <b>@{{ schedule.last_name }}</b></span><br /><br />
                       <span class="details_schedule">Customer Address: <b>@{{ schedule.service_address }}</b></span><br /><br />
                       <span class="details_schedule">Customer State: <b>@{{ schedule.city_state }}</b></span><br /><br />
                       <span class="details_schedule">Service Type: <b>@{{ schedule.service_type }}</b></span><br /><br />
                       <span class="details_schedule">Service Day: <b>@{{ schedule.service_day }}</b></span><br /><br />
                       <span class="details_schedule">Service Time: <b>@{{ schedule.service_time }}</b></span><br /><br />
                       <span class="details_schedule">Service Package: <b>@{{ schedule.service_package }}</b></span><br /><br />
                       <span class="details_schedule">Service Amount: <b>@{{ schedule.service_amount | currency }}</b></span><br /><br />
                       <span class="details_schedule">Service Job Details: <b>@{{ schedule.service_job_details }}</b></span><br /><br />
                       <span class="details_schedule">Service Folding Details: <b>@{{ schedule.service_folding_details }}</b></span><br /><br />
                       <span class="details_schedule">Service Hanging Details: <b>@{{ schedule.service_hanging_details }}</b></span><br /><br />
                       <span class="details_schedule">Service Washing Details: <b>@{{ schedule.service_washing_details }}</b></span><br /><br />

                   </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               </div>
           </div>
       </div>
   </div>


</div>

<!-- modal -->
<div class="modal fade" id="edit-photo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
				<h2 class="modal-title text-center">Edit Photo</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
                </button>
            </div>			
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <div id="upload-demo" style="width:300px;"></div>
                            <div class="col-md-12 col-sm-12 col-lg-12 marg-15">
                                <button class="vanilla-rotate btn btn-success" data-deg="-90">Rotate Left</button>
                                <button class="vanilla-rotate btn btn-success" data-deg="90">Rotate Right</button>
                            </div>
							<button type="button" class="choose-file-btn">Choose Image</button>
							<input type="file" id="upload" style="visibility:hidden;" accept="image/*" />
							<br/>
                            <div class="err"></div>
                     <input type="hidden" name="userType" id="userType" value="laundress">
							<button type="button" class="btn btn-success upload-result" style="display: none;">Upload Image</button>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection