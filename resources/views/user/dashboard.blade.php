@extends('layouts.main')
@section('content')
<div class="ng-app" ng-app="UserApp" ng-controller="UserCtrl">
<section class="heading_top">
   <div class="container">
      <div class="avatar_info">
         <div class="avatar profile-img">
            <img class="user-edit-img" src="{{asset('uploads/profiles')}}/{{$profile->profile_pic}}" alt="{{$profile->first_name}} {{$profile->last_name}}" onerror="this.onerror=null;this.src='{{asset("uploads/profiles/l60Hf.png")}}'"/>
         </div>
         <div class="avatar-info">
            <h3>{{$profile->first_name}} {{$profile->last_name}}</h3>
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
         <div class="border_bx_acc">
            <div class="col-md-2">
               <!-- Nav tabs -->
               <ul class="nav nav-tabs nav-tabs-dropdown" role="tablist">
                  <li role="presentation" class="@if($tab_id == 'dashboard') active @endif">
                     <a href="#dashboard" aria-controls="dashboard" role="tab" data-toggle="tab">
                        <span class="bor_rad">
                           <img src="{{asset('img/dashboard.png')}}" alt="dashboard"/>
                        </span> <b>Dashboard</b>
                     </a>
                  </li>
                  <li role="presentation"  class="@if($tab_id == 'profile') active @endif">
                     <a href="#account" aria-controls="account" role="tab" data-toggle="tab">
                        <span class="bor_rad">
                           <img src="{{asset('img/account.png')}}" alt="account"/>
                        </span> <b>Account</b>
                     </a>
                  </li>
                  <li role="presentation"class="@if($tab_id == 'schedule') active @endif">
                     <a href="#schedule" aria-controls="schedule" role="tab" data-toggle="tab">
                        <span class="bor_rad">
                           <img src="{{asset('img/schedule.png')}}" alt="schedule"/>
                        </span> <b>Schedule</b>
                     </a>
                  </li>
                  <li role="presentation" class="@if($tab_id == 'prevserv') active @endif">
                     <a href="#prevserv" aria-controls="prevserv" role="tab" data-toggle="tab">
                        <span class="bor_rad">
                           <img src="{{asset('img/prev_service.png')}}" alt="prev_service"/>
                        </span> <b>Previous Service</b>
                        </a></li>
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
                           <div class="col-md-12" ng-if="showTodayBookings">
                              <table class="table">
                                 <tr>
                                    <th>Time</th>
                                    <th>Date</th>
                                    <th>Payment Type</th>
                                    <th>Services</th>
                                    <th>Location</th>
                                    <th>Provider</th>
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
                           <div class="col-md-12" ng-if="!showTodayBookings">
                              <table class="table">
                                 <tr>
                                    <th>Time</th>
                                    <th>Date</th>
                                    <th>Payment Type</th>
                                    <th>Services</th>
                                    <th>Location</th>
                                    <th>Provider</th>
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
                           <!-- <div class="col-md-3">
                              <table class="table">
                                 <tr>
                                    <th colspan="2">Estimated Earnings</th>
                                 </tr>
                                 <tr>
                                    <td colspan="2">12/9 >>> 12/15</td>
                                 </tr>
                                 <tr>
                                    <td colspan="2">
                                       <table>
                                          <tr>
                                             <td><span class="line line-green"></span> Washing</td>
                                             <td>$450</td>
                                          </tr>
                                          <tr>
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
                                          </tr>
                                       </table>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>Weekly Revenue</td>
                                    <td>$1140</td>
                                 </tr>
                              </table>
                           </div> -->
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
                                 <input type="text" name="address" class="form-control" name="address" ng-model="user.address" required/>
                              </div>
                              <div class="form-group">
                                 <label>City/State</label>
                                 <input type="text" name="city_state" class="form-control" name="city_state" ng-model="user.city_state" required/>
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
                                    <input type='text' id="to_date_picker" ng-disabled="!custom.from_date" ng-change="showCustomBookings = false;showTodBookings = true;showTomBookings = true; showWeekBookings = true; showMonthBookings = true; customresultfun()" ng-model="custom.to_date" name="to_date" class="form-control" placeholder="To Date"/>
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
                                          <td>Total Amount</td>
                                          <td>Action</td>
                                       </tr>
                                       <tr ng-repeat="booking in schedulebookings.today_bookings" ng-if="!showTodBookings">
                                          <td>@{{booking.service_time}}</td>
                                          <td>@{{booking.service_day}}</td>
                                          <td>@{{booking.service_type}}</td>
                                          <td>@{{booking.service_payment_type}}</td>
                                          <td>@{{booking.service_package}}</td>
                                          <td>@{{booking.service_amount | currency}}</td>
                                          <td><a href="javascript:void(0);" ng-click="viewBooking(booking)" title="View Booking"><i class="fa fa-eye"></i></a> <a href="javascript:void(0);" ng-click="cancelBooking(booking)" title="Cancel Booking"><i class="fa fa-trash"></i> </a> </td>
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
                                          <td>@{{booking.service_amount | currency}}</td>
                                          <td><a href="javascript:void(0);" ng-click="viewBooking(booking)" title="View Booking"><i class="fa fa-eye"></i></a> <a href="javascript:void(0);" ng-click="cancelBooking(booking)" title="Cancel Booking"><i class="fa fa-trash"></i> </a> </td>
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
                                          <td>@{{booking.service_amount | currency}}</td>
                                          <td><a href="javascript:void(0);" ng-click="viewBooking(booking)" title="View Booking"><i class="fa fa-eye"></i></a> <a href="javascript:void(0);" ng-click="cancelBooking(booking)" title="Cancel Booking"><i class="fa fa-trash"></i> </a> </td>
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
                                          <td>@{{booking.service_amount | currency}}</td>
                                          <td><a href="javascript:void(0);" ng-click="viewBooking(booking)" title="View Booking"><i class="fa fa-eye"></i></a> <a href="javascript:void(0);" ng-click="cancelBooking(booking)" title="Cancel Booking"><i class="fa fa-trash"></i> </a> </td>
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
                                          <td>@{{booking.service_amount | currency}}</td>
                                          <td><a href="javascript:void(0);" ng-click="viewBooking(booking)" title="View Booking"><i class="fa fa-eye"></i></a> <a href="javascript:void(0);" ng-click="cancelBooking(booking)" title="Cancel Booking"><i class="fa fa-trash"></i> </a> </td>
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
                                    <th>Provider</th>
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
                                    <td><b>@{{booking.first_name}} @{{booking.last_name}}</b> </td>
                                    <td ng-if="booking.status == 'new'" ><b>Active</b> </td>
                                    <td ng-if="booking.status != 'new'" ><b>@{{booking.status}}</b> </td>
                                    <td>
                                       <a href="javascript:void(0);" ng-click="viewBooking(booking)" title="View Booking">
                                          <i class="fa fa-eye"></i>
                                       </a> 
<!--                                        <a href="javascript:void(0);" ng-if="booking.status === 'completed'" class="disabled">
                                          <i class="fa fa-check"></i> 
                                       </a>  -->
                                       <a href="javascript:void(0);" ng-click="completeBooking(booking)" ng-if="booking.status !== 'completed'" title="Mark Booking Complete">
                                          <i class="fa fa-check"></i> 
                                       </a> 
                                       </td>
                                 </tr>
                                 <tr ng-if="!bookings.past_bookings.length">
                                    <td class="full-width text-center">No bookings available.</td>
                                 </tr>
                              </table>
                           </div>
                  </div>
                  <div role="tabpanel" class="tab-pane" id="logout">...</div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
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
                     
                    <span class="details_schedule">Provider First Name: <b>@{{ schedule.first_name }}</b></span><br /><br />
                    <span class="details_schedule">Provider Last Name: <b>@{{ schedule.last_name }}</b></span><br /><br />
                    <span class="details_schedule">Service Address: <b>@{{ schedule.service_address }}</b></span><br /><br />
                    <span class="details_schedule">Provider State: <b>@{{ schedule.city_state }}</b></span><br /><br />
                    <span class="details_schedule">Service Type: <b>@{{ schedule.service_type }}</b></span><br /><br />
                    <span class="details_schedule">Service Day: <b>@{{ schedule.service_day }}</b></span><br /><br />
                    <span class="details_schedule">Service Time: <b>@{{ schedule.service_time }}</b></span><br /><br />
                    <span class="details_schedule">Service Package: <b>@{{ schedule.service_package }}</b></span><br /><br />
                    <span class="details_schedule">Service Tax: <b>@{{ schedule.service_tax | currency }}</b></span><br /><br />
                    <span class="details_schedule">Service Amount: <b>@{{ schedule.service_amount | currency}}</b></span><br /><br />
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
<!-- End Team section-->

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
                     <input type="hidden" name="userType" id="userType" value="user">
							<button type="button" class="btn btn-success upload-result" style="display: none;">Upload Image</button>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection