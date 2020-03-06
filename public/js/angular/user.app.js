var app = angular.module('UserApp', []);
var modal = document.getElementById("viewSchedule");
app.controller('UserCtrl', function($scope, $http, $timeout) {
  
  $scope.user = {current_password: '', password: '', confirm_password: ''};
  $scope.custom = {};
  $scope.showTodayBookings = true;
  $scope.viewBookings = false;
  $scope.showTodBookings = false;
  $scope.showTomBookings = true;
  $scope.showWeekBookings = true;
  $scope.showMonthBookings = true;
  
  /*
  * Get User Profile
  */
  $http.get('/user-profile/me')
  .then(function(response) {
    $scope.user = response.data.data;
    $scope.user.current_password = '';
    $scope.user.password =  '';
    $scope.user.confirm_password = '';
    $scope.getSchedule();
    $scope.viewSchedulelist();
  });

  /*
  * Update Profile
  */
  $scope.updateProfile = function() {  	
  	if($scope.user.password && ($scope.user.password !== $scope.user.confirm_password)) {
  		$scope.errorMessage = 'Password and confirm password not matched.';
  	} else if($scope.user.password != '' && ($scope.user.password == $scope.user.confirm_password) && $scope.user.current_password !== '') {
  		$scope.updateProfileAjax();
  	} else if(($scope.user.password == '' || !$scope.user.password) && ($scope.user.confirm_password == '' || !$scope.user.confirm_password) && ($scope.user.current_password == '' || !$scope.user.current_password)){
  		$scope.updateProfileAjax();
  	} else {
  		$scope.errorMessage = 'Please enter your current password.';
  	}
  }

  /*
  * Call Update Profile 
  */
  $scope.updateProfileAjax = function() {
  	$http.post('/update-profile', $scope.user)
		.then(function (response) {
		  	var response = response.data;
		  	if(response.success) {
		  		swal('Profile Updated', 'Your profile details has been updated.', "success");
		  	} else {
		  		swal('Error', response.message, "error");
		  	}
		},function(error){
	      swal(error.status.toString(), error.data.message, "error");
		});
  }

   $scope.customresultfun = function(){
    //console.log($scope.custom);
    if($scope.custom.to_date){
      
      $http.post('/user-view-schedule-custom',$scope.custom)
        .then(function (response) {
          var response = response.data;
          $scope.customresultbookings = response.customresult;
        }, function (error) {
          console.log('error getting schedule ', error);
      });
    }
  }

  /*
  * Get User Schedule
  */
  $scope.getSchedule = function() {
  	$http.get('/user-schedule')
  	.then(function (response) {
  		var response = response.data;
  		$scope.bookings = response.bookings;
  	}, function (error) {
  		console.log('error getting schedule ', error);
  	});
  }

    $scope.viewSchedulelist = function(){
      $http.get('/user-view-schedule')
      .then(function (response) {
        var response = response.data;
        $scope.schedulebookings = response.bookings;
      }, function (error) {
        console.log('error getting schedule ', error);
      });
    }

  $scope.viewBooking = function(scheduledata){

    $('#viewSchedule').modal('show');
    $scope.schedule = scheduledata;
  }

  $scope.closeviewSchedule = function(){
    $('#viewSchedule').modal('hide');
  }

  /*
  * Scroll to Div
  */
  $scope.scroll = function(el) {
  	$('html, body').animate({
	    scrollTop: $("." + el).offset().top
	  }, 2000);
  }

  $scope.cancelBooking = function(booking) {
    
    $http.post('/cancel-booking-amount', {id: booking.id})
    .then(function (response) {
      var response = response.data;
      swal({
          title: 'Cancel Booking?',
          text: "Are you sure you want to cancel this booking. You will be charged $" + response.amount + ".",
          icon: "warning",
          buttons: true,
          dangerMode: true,
      })
      .then((confirm) => {
        if(confirm) {
          $http.post('/cancel-booking', {id: booking.id})
          .then(function (response) {
            $scope.getSchedule();
            $scope.viewSchedulelist();
            swal('Booking Canceled.', "This booking has been canceled", "success");
          }, function (error) {
            swal(error.status.toString(), error.data.message, "error");
          });
        }
      });
    }, function (error) {
      console.log('error getting schedule ', error);
    });
  }

  $scope.completeBooking = function(booking) {
      swal({
          title: 'Complete Booking?',
          text: "Are you sure you want to complete this booking",
          icon: "warning",
          buttons: true,
          dangerMode: true,
      })
      .then((confirm) => {
        if(confirm) {
          $http.post('/complete-booking', {id: booking.id})
          .then(function (response) {
            $scope.getSchedule();
            $scope.viewSchedulelist();
            swal('Booking Completed.', "This booking has been completed", "success");
          }, function (error) {
            swal(error.status.toString(), error.data.message, "error");
          });
        }
      });
  }

});