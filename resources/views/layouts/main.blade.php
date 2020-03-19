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
    <link rel="stylesheet" href="{{asset('css/bootstrap-datepicker.min.css')}}">
    <link href="{{asset('css/sweetalert.css')}}" type="text/css" rel="stylesheet" />
  </head>
  <body>
    <div id="load"></div>
     <div class="page">
      <!-- Start Nav Section-->
      <nav id="main-navigation" role="navigation" class="navbar navbar-fixed-top navbar-standard">
        <div class="container">
          <div class="navbar-header">
		 
            <button aria-controls="bs-navbar" aria-expanded="false" class="navbar-toggle collapsed" data-target="#bs-navbar" data-toggle="collapse" type="button"><i class="fa fa-align-justify fa-lg"></i></button><a href="/" class="navbar-brand"><img src="{{ asset('/img/logo.png')}}" alt="logo" ></a>
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
              <li><a href="{{ route('viewpartTeam') }}" title="Login">Join Team</a></li>
              @endif
		          @guest
              <li class="ajax-menu-login"><a href="{{ route('login') }}" title="Login">Login</a>
              </li>
              @else
                <li><a href="/user/profile" class="">Profile</a></li>
              @endif

              <li class="ajax-menu login"><a href="{{ route('login') }}" title="Login">Login</a>
              </li>
              <li class="ajax-menu profile"><a href="/user/profile" class="">Profile</a></li>
			  
              
            </ul>
			
			<ul id="right-page-menu" class="nav navbar-nav navbar-left btn_nav">
              <li><a href="/book" class="phn">Book us Now</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      
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
               <div class="copyright">Copyright 2020 The Cesta</div>
              </div>
			  
			  
            </div>
          </div>
        </div>
        
      </footer>
      <!-- End Footer section-->

      <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-left">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="login px-md-4 text-center">
                        <h3 class="mb-4">LOG INTO YOUR ACCOUNT</h5>
                        <span class="invalid-feedback" role="alert">
                            <strong>You have entered an invalid Email or password.</strong>
                        </span>
                        <form method="POST" action="{{ route('login') }}" id="loginForm">
                            @csrf
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <!--<label class="mb-2">Email</label>-->
                                  <input type="email" placeholder="Email" class="form-control" id="user_email" name="email" aria-describedby="emailHelp" placeholder="" required="">                           
                                </div>
                                <div class="form-group">
                                  <!--<label class="mb-2">Password</label>-->
                                  <input type="password" placeholder="Password" class="form-control" id="user_password" name="password" placeholder="" required="">
                                </div>
                              </div>
                            </div>
                            <div class="row text-left mb-2">
                              <div class="col-md-12">
                                <input type="checkbox" name="remember" /><span class="remember">Remember me</span>
                              </div>
                            </div>              
                            <div class="row mb-2">
                              <div class="col-md-12">
                                <div class="form-group text-center">
                                  <button type="button" class="login-btn login-submit-btn submit_log" name="log-in">LOGIN</button>                         
                                </div>
                              </div>
                            </div>
                            
                            <div class="row forgot-grid">
                              <div class="col-md-6 col-6 text-left">
                                <a href="{{url('password/reset')}}">Forgot my password</a>
                              </div>
                              <div class="clearfix"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
      </div>
      
    </div>
    
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.js" type="text/javascript"></script>
  <script src="{{ asset('js/popper.min.js')}}" type="text/javascript"></script>
  <script src="{{ asset('js/bootstrap.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{ asset('js/moment.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js')}}"></script>
  <script src="{{ asset('js/bootstrap-datepicker.min.js')}}"></script> 
  <script src="{{asset('js/croppie.js')}}"></script>
  
  <!-- validation lib -->
  <script type="text/javascript" src="{{asset('js/validator.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/custom.js')}}"></script>
  <script src="{{asset('js/sweetalert.min.js')}}"></script>
  
  <!-- angularjs -->
  <script type="text/javascript" src="{{asset('js/angular/angular.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/angular/laundress.app.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/angular/user.app.js')}}"></script>
	</body>
</html>