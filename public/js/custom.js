$(function () {
      $('#msgError').hide();
      $('#msgSuccess').hide();

      $("input[name='service_type']").each(function() {
        if($(this).is(':checked')) {
          $('.next_serv').show();
        }
      });

      $('#service_day').datepicker({
           autoclose: true,
           startDate: "dateToday"
        });

      var dateFormat = "m/d/Y",
        from = $( "#from_date_picker" )
          .datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 3
          })
          .on( "change", function() {
            to.datepicker( "option", "minDate", getDate( this ) );
            //console.log('Yes From');
          }),
        to = $( "#to_date_picker" ).datepicker({
          defaultDate: "+1w",
          changeMonth: true,
          numberOfMonths: 3
        })
        .on( "change", function() {
          from.datepicker( "option", "maxDate", getDate( this ) );
          //console.log('Yes To');
        });
   
      function getDate( element ) {
        var date;
        try {
          date = $.datepicker.parseDate( dateFormat, element.value );
        } catch( error ) {
          date = null;
        }
   
        return date;
      }

      
      $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
      });
      $('.sel').click(function(){
        $('.next_serv').show();
      })
      $('.ctn-btn').click(function(){
        activeTab($(this).data('tab'));
      });

      //get user details if booking page and auto fill sesion fields
      // if($('#bookingForm') && $('#bookingForm').length) {
      //   $.ajax({
      //     url: "/user/me",
      //     type: "GET",
      //     dataType: 'JSON',
      //     success: function(user) {
      //       if(user.loggedIn) {
      //         console.log('user data ', user.data);
      //       }
      //     },
      //     error: function(error) {
      //       console.log('Can\'t get current user ', error);
      //     }
      //   })
      // }


      var totalPrice = 0;
      var basicPrice = 0;
      //change price when service category checked
      $("input[name='service_categories[]']").change(function() {
        var category = $(this).val().toUpperCase() + '_PRICE';
        var envPrice = parseFloat($('#'+category).val()).toFixed(2);
        var quantity = parseFloat($('#service_quantity').val()).toFixed(2);
        var checkPrice = parseFloat(envPrice * quantity);
        if($(this).is(":checked")) {
          totalPrice = parseFloat(parseFloat(totalPrice) + parseFloat(checkPrice)).toFixed(2);          
          basicPrice = parseFloat(parseFloat(basicPrice) + parseFloat(envPrice)).toFixed(2);
        } else {
          totalPrice = parseFloat(parseFloat(totalPrice) - parseFloat(checkPrice)).toFixed(2);
          basicPrice = parseFloat(parseFloat(basicPrice) - parseFloat(envPrice)).toFixed(2);
        }

        $('.total_price').val(totalPrice);
        $('#main_price').val(basicPrice);
      });


      //signup from booking page
      $('#bookingSubmit').click(function(e) {
          e.preventDefault();
          if(validateBookingForm('payment')) {
            var btn = $(this);
            var form = $('#bookingForm');
            var url = form.attr('action');
            btn.text('Please wait..');
            $.ajax({
                   type: "POST",
                   url: url,
                   data: form.serialize(), // serializes the form's elements.
                   success: function(response){
                      btn.text('Submit Payment');
                      console.log(response); // show response from the php script.
                      $('#msgSuccess').html(response.message);
                      $('#msgSuccess').show();
                      scrollToDiv('msgSuccess');
                      setTimeout(function() {
                        window.location.href = response.return_url;
                      },3000);
                   },
                   error: function(error) {
                    scrollToDiv('msgError');
                    btn.text('Submit Payment');
                    $('#msgError').html(error.responseJSON.message);
                    $('#msgError').show();
                    setTimeout(function() {
                      $('#msgError').fadeOut('slow');
                    },3000);
                   }
                 });
          }

      });
      //change total price
      $('#service_quantity').on('input', function() {
        $('.total_price').val($(this).val() * $('#main_price').val())
      })

      //open profile image modal
      $(".choose-file-btn").click(function() { 
          $("#upload").trigger('click');
      });

      //profile image upload
      var $uploadCrop = "";
      $('#upload').on('change', function() {

          var value = $(this).val(),
              file = value.toLowerCase(),
              extension = file.substring(file.lastIndexOf('.') + 1);

          $(".err").html("")
          let allowedExtensions = ['jpg', 'jpeg', 'png']
          if ($.inArray(extension, allowedExtensions) == -1) {
              $(".err").html("<p style='color:red;'>Please select only: jpg, jpeg, png format.</p>");
              return false;
          }

          $('#upload-demo').croppie('destroy');
          $('.upload-result').show();
          $uploadCrop = $('#upload-demo').croppie({
              enableExif: true,
              viewport: {
                  width: 200,
                  height: 200
              },
              boundary: {
                  width: 300,
                  height: 300
              }
          });

          var reader = new FileReader();
          reader.onload = function(e) {
              $uploadCrop.croppie('bind', {
                  url: e.target.result
              }).then(function() {
              });
          }
          reader.readAsDataURL(this.files[0]);
      });
      $('.upload-result').on('click', function(ev) {
          $uploadCrop.croppie('result', {
              type: 'canvas',
              size: 'viewport'
          }).then(function(resp) {
              var userType  = $('#userType').val();
              $.ajax({
                  url: "/"+ userType +"/upload/profile",
                  type: "POST",
                  data: {
                      "image": resp
                  },
                  dataType: 'JSON',
                  success: function(data) {
                      $("#edit-photo").modal("hide");
                      $(".profile-img img").attr('src', resp)
                      $('#upload-demo').croppie('destroy');
                      $('.upload-result').hide();
                      $('#edit-photo').modal('hide');
                      if(data.success) {
                        swal('Profile Updated', 'Your profile picture has been updated.', "success");
                      } else {
                        swal('Error', data.message, "error");
                      }
                  },
                  error: function(err) {
                      swal("Error!", "Please try again", "error");
                  }
              });
          });
      });
      //
      $('#service_laundress').change(function(){
        if($(this).val()) {
          $('#service_laundress_selected').val($('#service_laundress option:selected').html());
        }
      });

      $('#service_time').change(function(){
        if($(this).val()) {
          $('#service_time_selected').val($('#service_time option:selected').html());
        }
      });

      $('#service_day').change(function(){
        if($(this).val()) {
          $('#service_day_selected').val($('#service_day option:selected').html());
        }
      });

      //login modal
      $(".submit_log").click(function(e) {
          e.preventDefault();
          $(this).text('Please wait..');
          ajax_login();
      });

      $('#password').keypress(function(event){
          var keycode = (event.keyCode ? event.keyCode : event.which);
          if(keycode == '13'){
              $('.submit_log').text('Please wait..');
              ajax_login();
          }
      });

      // var name = '';
      //fill last tab data from register form
      $('.register-details').keyup(function(){
          if($(this).attr('name') == 'register[email]') {
            $('.service_email').val($(this).val());
          } else if($(this).attr('name') == 'register[phone]') {
            $('.service_phone').val($(this).val());
          } else if($(this).attr('name') == 'register[zip]') {
            $('.service_zip').val($(this).val());
          } else if($(this).attr('name') == 'register[first_name]') {
            $('.service_contact_name').val($(this).val());
          } else if($(this).attr('name') == 'register[last_name]') {
            //$('.service_contact_name').val(name);
          }
      });

    });
    function activeTab(tab){
        //check validations and restrict the next tab
        if(validateBookingForm(tab)) {
        	$('.tab-pane').removeClass('active');
        	$('#' + tab).addClass('active');
        	$('.nav.nav-tabs.nav-tabs-dropdown.horizontal li').removeClass('active');
        	$('.tab_' + tab).addClass('active');
        	scrollToDiv(tab);
        }
    };

    function scrollToDiv(divId) {
      $('html, body').animate({
          scrollTop: $("#" + divId).offset().top - 300
      }, 500);
    }

    function ajax_login() {
        var loginForm = $("#loginForm");
        var formData = loginForm.serialize();
        var formurl = loginForm.attr('action');
        $(".invalid-feedback").hide();
        $.ajax({
            url: formurl,
            type:'POST',
            data:formData,
            success:function(data) {    
                
                if(data && data.auth) {       
                    console.log(data);
                    updateUserDetails(data.user);
                    $('.not-logged').hide();
                    $('.logged-in').show();
                    activeTab('loading');
                    $('#login-modal').modal('hide');
                }
                $('.submit_log').text('LOGIN');
            },
            error: function (data) {
                $(".invalid-feedback").show();
                console.log("error",data);
                $('.submit_log').text('LOGIN');
            }
        });
    }

    function updateUserDetails(user) {
      $('#service_address').val(user.address);
      $('#service_zip').val(user.zip);
      $('#service_contact_name').val(user.first_name + ' ' + user.last_name);
      $('#service_email').val(user.email);
      $('#service_phone').val(user.phone);
      $('#isLoggedIn').val('1');
    }

    function validateBookingForm(tabId) {
      	$('#bookingValidationError').hide();
      	var response = false;
      	var message = '';
	    if(tabId == 'when') {
	      	//console.log($("input[name='service_type']").is(":checked") , $("input[name='service_categories[]']").is(":checked"), tabId);
	        if($("input[name='service_type']").is(":checked") && $("input[name='service_categories[]']").is(":checked")) {
	          response = true;
	        } else {
	          message = 'Select your add on service.';
	        }
	    } else if(tabId == 'contact') {
	        if($('#service_laundress option:selected').val() != '') {
	          var service_laundress = true;
	        } else {
	          message = 'Choose your laundress.';
	        }
	        if($('#service_day').val() != '') {
	          var service_day = true;
	        } else {
	          message = 'Choose your service day.';
	        }
	        if($('#service_time option:selected').val() != '') {
	          var service_time = true;
	        } else {
	          message = 'Choose your service day.';
	        }

	        if(service_laundress && service_day && service_time) {
	          response = true;
	        }
	    } else if(tabId == 'loading') {
	        var registerCount = 0;
	        $('.register-details').each(function() {
	          message = 'Please enter all required details.';
	          if($(this).val() != '') {
	            registerCount++;
	          } else if($(this).attr('name') === 'register[email]' && /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($(this).val())) {
	            registerCount++;
	          } else {
	            // message = 'Please enter valid email address.';
	          }
	        });
	        if(registerCount == 6) {
	          response = true;
	        }
	        //if user is logged in
	        if($('#isLoggedIn').val() == '1') {
	          response = true;
	        }
	    } else if(tabId == 'detail') {
	        if($("input[name='service_package']").is(":checked")) {
	          response = true;
	        } else {
	          message = 'Select your press package.';
	        }
	    } else if(tabId == 'payment') {
	        response = true;
	        if($('#user_address').val() == '') {
	          message = 'Please enter biliing address';
	          response = false;
	        }
	        if($('#user_city').val() == '') {
	          message = 'Please enter biliing city.';
	          response = false;
	        }
	        if($('#user_state option:selected').val() == '') {
	          message = 'Please enter biliing state.';
	          response = false;
	        }
	        if($('#zip option:selected').val() == '') {
	          message = 'Please enter biliing zip.';
	          response = false;
	        }
	        if($('#user_country option:selected').val() == '') {
	          message = 'Please enter biliing country.';
	          response = false;
	        }
	        if($('#card_number').val() == '') {
	          message = 'Please enter card number.';
	          response = false;
	        }
	        if($('#card_name').val() == '') {
	          message = 'Please enter card name.';
	          response = false;
	        }
	        if($('#card_security_code').val() == '') {
	          message = 'Please enter card security code.';
	          response = false;
	        }
	        if($('#service_description').val() == '') {
	          message = 'Please enter service description.';
	          response = false;
	        }
          if($('#zip').val() == '') {
            message = 'Please enter zip code.';
            response = false;
          }
	    }

	    //show validation message
	    if(!response) {
	        $('#bookingValidationError p').html(message);
	        $('#bookingValidationError').show();
	        scrollToDiv('bookingValidationError');
	        setTimeout(function () {
	          $('#bookingValidationError').fadeOut('slow');
	        },3000);
	    }

	    return response;
    }

    function checkMaxLen(event, max) {
      if(event.target.value.length > max) {
        return false;
      }
    }

    function startOver() {
      $('.tab-pane').removeClass('active'); 
      $('#services').addClass('active');
    }