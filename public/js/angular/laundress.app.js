var app = angular.module('laundressUserApp', []);
var modal = document.getElementById("viewSchedule");
app.controller('laundressUserCtrl', function($scope, $http, $timeout) {
  
  $scope.user = {current_password: '', password: '', confirm_password: ''};
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

    $scope.getWeekEarnings();
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
		  		$scope.successMessage = response.message;
		  	} else {
		  		$scope.errorMessage = response.message;
		  	}
		  	$timeout(function () {
		  		$scope.successMessage = '';
		  		$scope.errorMessage = '';
		  	},3000);
		},function(error){
		        $scope.errorMessage = error.data.message;
		        $timeout(function () {
			  		$scope.errorMessage = '';
			  	},3000);
		});
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
        // var response = response.data;
        $scope.earningsData = response.data;
        // $scope.weekStart = response.weekStart;
        // $scope.weekEnd = response.weekEnd;
        console.log(response);
      }, function (error) {
        console.log('error getting weekEarnings ', error);
      });
  }

});