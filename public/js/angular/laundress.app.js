var app = angular.module('laundressUserApp', []);
var modal = document.getElementById("viewSchedule");
app.controller('laundressUserCtrl', function($scope, $http, $timeout) {
  
  $scope.user = {current_password: '', password: '', confirm_password: ''};
  $scope.bank = {};
  $scope.custom = {};
  $scope.successMsg = '';
  $scope.errMsg = '';
  $scope.showTodayBookings = true;
  $scope.viewBookings = false;
  $scope.showTodBookings = false;
  $scope.showTomBookings = true;
  $scope.showWeekBookings = true;
  $scope.showMonthBookings = true;
  $scope.showCustomBookings = true;
  $scope.totalPayment = 0;
  $scope.showBankAccount = false;
  $scope.payIds = [];

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
    $scope.getWeekEarnings();
    $scope.getBankDetails();
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
      
      $http.post('/laundress-view-schedule-custom',$scope.custom)
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
  	$http.get('/laundress-schedule')
    .then(function (response) {
      var response = response.data;
      $scope.bookings = response.bookings;
    }, function (error) {
      console.log('error getting schedule ', error);
    });
  }

  $scope.viewSchedulelist = function(){
    $http.get('/laundress-view-schedule')
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

  $scope.getWeekEarnings = function () {
     $http.get('/earnings-by-week')
      .then(function (response) {
        $scope.earningsData = response.data;
      }, function (error) {
        console.log('error getting weekEarnings ', error);
      });
  }

  $scope.cancelBooking = function(booking) {
    swal({
        title: 'Decline Booking?',
        text: "Are you sure you want to decline this booking.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((confirm) => {
      if (confirm) {
        $http.post('/decline-booking', {id: booking.id})
        .then(function (response) {
          $scope.getSchedule();
          $scope.viewSchedulelist();
          swal('Booking Declined.', "This booking has been declined.", "success");
        }, function (error) {
          swal(error.status.toString(), error.data.message, "error");
        });
      }
    });
  }

  $scope.updateBankAccount = function () {
    $http.post('/update-account', $scope.bank)
    .then(function (response) {
      var data = response.data;
      if(response.status) {
        swal("Success", "Bank details has been updated.", "success");
      } else {
        swal("Error", data.message, "error");
      }      
    }, function (error) {
      swal(error.status.toString(), error.data.message, "error");
    });
  }

  $scope.getBankDetails = function () {
    $scope.totalPayment = 0;
    $http.get('/get-account')
    .then(function (response) {
      var data = response.data;
      $scope.bank = data.message;
      $scope.payments = data.bookings;
      $scope.payments.map(p => {
        if(p.payment_request === '0') {
          $scope.totalPayment = $scope.totalPayment + parseFloat(p.service_amount);
          $scope.payIds.push(p.id);
        }
      })
    }, function(error) {
      console.log('error getting bank details ', error);
    });
  }

  $scope.requestPayment = function () {
    swal({
        title: 'Request Payment?',
        text: "Are you sure you want to request for payment.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((confirm) => {
      if(confirm) {
        $http.post('/request-payment', {book_ids:$scope.payIds})
        .then(function (response) {
          var data = response.data;
          if(response.status) {
            swal("Success", "Payment request has been sent to admin.", "success");
            $scope.getBankDetails();
          } else {
            $scope.errMsg = data.message;
            swal("Error", data.message, "error");
          }
        }, function (error) {
          swal(error.status.toString(), error.data.message, "error");
        });
      }
    });
  }

});