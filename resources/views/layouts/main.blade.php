<!DOCTYPE html>
<html class="no-js">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>The Cestal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
	  <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">
    <link rel="stylesheet" href="{{asset('css/croppie.css')}}">
  </head>
  <body>
    <div id="load"></div>
     <div class="page">
      <!-- Start Nav Section-->
      <nav id="main-navigation" role="navigation" class="navbar navbar-fixed-top navbar-standard">
        <div class="container">
          <div class="navbar-header">
		 
            <button aria-controls="bs-navbar" aria-expanded="false" class="navbar-toggle collapsed" data-target="#bs-navbar" data-toggle="collapse" type="button"><i class="fa fa-align-justify fa-lg"></i></button><a href="index.html" class="navbar-brand"><img src="{{ asset('/img/logo.png')}}" alt="logo" ></a>
          </div>
          <div class="navbar-collapse collapse" id="bs-navbar">
           
            <ul id="one-page-menu" class="nav navbar-nav navbar-left">
              <li><a class="active" href="#" title="About">About</a>
              </li>
              <li><a href="#" title="Services">Services</a>
              </li>
              <li><a href="#" title="Reviews">Reviews</a>
              </li>
		          @guest
              <li><a href="{{ route('login') }}" title="Login">Login</a>
              </li>
              @else
                <li><a href="/profile" class="">Profile</a></li>
              @endif
			  
              
            </ul>
			
			<ul id="right-page-menu" class="nav navbar-nav navbar-left btn_nav">
              <li><a href="/book" class="phn">Book us Now</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      @if(Session::has('message'))
        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
          {{ Session::get('message') }}
      </p>
      @endif
      	@yield('content')

      <!-- Start Footer section-->
      <footer id="footer">
        <div class="inner sep-bottom-md">
		<img class="foot_dot" src="{{ asset('/img/footer_dottedline.png')}}" alt="footer_dottedline" >
          <div class="container">
            <div class="row">
			
			<div class="col-md-12 col-sm-12">
                <div class="widget text-center "><img src="{{ asset('/img/footer_logo.png')}}" alt="footer_logo" >
                </div>
              </div>
			  <div class="col-md-12 col-sm-12">
			   <div class="social">
			     <ul>
				   <li><a href="#"><i class="fa fa-facebook"></i></a></li>
				   <li><a href="#"><i class="fa fa-instagram"></i></a></li>
				 </ul>
			   </div>
               <div class="copyright">Copyright 2019 The Cesta</div>
              </div>
			  
			  
            </div>
          </div>
        </div>
        
      </footer>
      <!-- End Footer section-->
      
    </div>
    
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.js" type="text/javascript"></script>
  <script src="{{ asset('js/popper.min.js')}}" type="text/javascript"></script>
  <script src="{{ asset('js/bootstrap.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{ asset('js/moment.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js')}}"></script>
  <script src="{{asset('js/croppie.js')}}"></script>
   <script type="text/javascript">
    $(function () {
      $('#msgError').hide();
      $('#msgSuccess').hide();
      $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
      });
      $('.sel').click(function(){
        $('.next_serv').show();
      })
      $('.ctn-btn').click(function(){
        activaTab($(this).data('tab'));
      });

      //get user details if booking page and auto fill sesion fields
      if($('#bookingForm') && $('#bookingForm').length) {
        $.ajax({
          url: "/user/me",
          type: "GET",
          dataType: 'JSON',
          success: function(user) {
            if(user.loggedIn) {
              console.log('user data ', user.data);
            }
          },
          error: function(error) {
            console.log('Can\'t get current user ', error);
          }
        })
      }


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
                      window.location.href = "{{ route('user_dashboard') }}";
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
                  console.log('jQuery bind complete');
              });
          }
          reader.readAsDataURL(this.files[0]);
      });
      $('.upload-result').on('click', function(ev) {
          $uploadCrop.croppie('result', {
              type: 'canvas',
              size: 'viewport'
          }).then(function(resp) {
              $.ajax({
                  url: "{{ route('upload_profile_picture') }}",
                  type: "POST",
                  data: {
                      "image": resp
                  },
                  success: function(data) {
                      $("#edit-photo").modal("hide");
                      $(".profile-img img").attr('src', resp)
                      $('#upload-demo').croppie('destroy');
                      $('.upload-result').hide();
                      $('#edit-photo').modal('hide');
                  },
                  error: function(err) {
                      swal("Error!", "Please try again", "error");
                  }
              });
          });
      });
    });
    function activaTab(tab){
        $('.nav-tabs a[href="#' + tab + '"]').tab('show');
        scrollToDiv(tab);
    };

    function scrollToDiv(divId) {
      $('html, body').animate({
          scrollTop: $("#" + divId).offset().top - 300
      }, 500);
    }
    </script>
	</body>
</html>