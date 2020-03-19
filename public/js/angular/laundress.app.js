var app = angular.module('laundressUserApp', []);

  /* Upload Image Directive */
  app.directive("ngUploadChange",function(){
          return{
              scope:{
                  ngUploadChange:"&"
              },
              link:function($scope, $element, $attrs){
                  $element.on("change",function(event){
                      $scope.$apply(function(){
                          $scope.ngUploadChange({$event: event})
                      })
                  })
                  $scope.$on("$destroy",function(){
                      $element.off();
                  });
              }
          }
  });
  /* Code Ends*/

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
  $scope.accUpdateBtn = 'Submit';

  $timeout(function () {
    $scope.getUser();
  },500);
  /*
  * Get User Profile
  */
  $scope.getUser = function () {    
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
  };

  /*
  * Update Profile
  */
  $scope.updateProfile = function() {   
    if($scope.user.password && ($scope.user.password !== $scope.user.confirm_password)) {
      swal('Error', 'Password and confirm password not matched.', "error");
    } else if($scope.user.password != '' && ($scope.user.password == $scope.user.confirm_password) && $scope.user.current_password !== '') {
      $scope.updateProfileAjax();
    } else if(($scope.user.password == '' || !$scope.user.password) && ($scope.user.confirm_password == '' || !$scope.user.confirm_password) && ($scope.user.current_password == '' || !$scope.user.current_password)){
      $scope.updateProfileAjax();
    } else if($scope.user.current_password !=''){
      if($scope.user.password == '') {
        swal('Error', 'Please enter your new password.', "error");
      } else if($scope.user.confirm_password == '') {
        swal('Error', 'Please enter your confirm password.', "error");
      } else {
        $scope.updateProfileAjax();
      }
    } else{
      swal('Error', 'Please enter your current password.', "error");
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
		    swal("Error", error.data.message, "error");
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
          swal("Error", error.data.message, "error");
        });
      }
    });
  }

  $scope.updateBankAccount = function () {
    $scope.accUpdateBtn = 'Please wait..';
    $http.post('/update-account', $scope.bank)
    .then(function (response) {
      var data = response.data;
      if(response.status) {
        $scope.getBankDetails();
        swal("Success", "Bank details has been updated.", "success");
        $scope.accUpdateBtn = 'Submit';
      } else {
        swal("Error", data.message, "error");
        $scope.accUpdateBtn = 'Submit';
      }      
    }, function (error) {
      var errMsg = error.data.message;
      for(var key in error.data.errors) {
        if(error.data.errors[key] && error.data.errors[key].length) {
          for (var i =0; i < error.data.errors[key].length; i++) {
            errMsg = errMsg + error.data.errors[key][i];
          }
        }
      }
      swal("Error", errMsg, "error");
      $scope.accUpdateBtn = 'Submit';
    });
  }

  $scope.getBankDetails = function () {
    $scope.totalPayment = 0;
    $http.get('/get-account')
    .then(function (response) {
      var data = response.data;
      $scope.bank = (data.message && data.message.bank_name !== '') ? data.extra_data : {};
      $scope.payments = data.bookings;
      console.log('data ',  $scope.bank,' Paymen ',$scope.payments);

      $scope.payments.map(p => {
        if(p.payment_request === '0') {
          let basePrice = parseFloat(p.service_amount - p.service_tax);
          let providerShare = (basePrice * 90) / 100;
          $scope.totalPayment = parseFloat(parseFloat($scope.totalPayment) + parseFloat(providerShare)).toFixed(2);
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
          swal("Error", error.data.message, "error");
        });
      }
    });
  }

  $scope.fileNameChanged = function (event, type) {
    //upload file first
    getBase64(event.target.files[0], type);
    $timeout(function (){
    $http.post('/upload-image',{image: $('#'+type).val()})
    .then(function (response) {
        var response = response.data;
        if(response.status) {
          if(type == 'front') {
            $scope.bank.front = response.path;
          } else {
            $scope.bank.back = response.path;
          }
        } else {
          swal('Error', response.message, "error");
        }
    },function(error){
        swal("Error", error.data.message, "error");
    });
    },1000);
  }
});

function getBase64(file, type) {
   var reader = new FileReader();
   reader.readAsDataURL(file);
   reader.onload = function () {
     $('#'+type).val(reader.result);
   };
   reader.onerror = function (error) {
     console.log('Error: ', error);
     return error;
   };
}