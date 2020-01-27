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
			  @if(Auth::user())
              <li><a href="{{ url(Auth::user()->user_type) }}" class="">{{ Auth::user()->first_name }}</a></li>
              @else
              <li><a href="{{ route('login') }}" title="Login">Login</a>
              </li>
              @endif
			  
              
            </ul>
			
			<ul id="right-page-menu" class="nav navbar-nav navbar-left btn_nav">
              <li><a href="#" class="phn">Book us Now</a>
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
               <div class="copyright">Copyright 2019 The Cesta</div>
              </div>
			  
			  
            </div>
          </div>
        </div>
        
      </footer>
      <!-- End Footer section-->
      
    </div>
    
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.js" type="text/javascript"></script>
    <script src="{{ asset('js/bootstrap.js')}}" type="text/javascript"></script>
   
	</body>
</html>