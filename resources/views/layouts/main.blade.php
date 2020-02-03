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
		          @guest
              <li><a href="{{ route('login') }}" title="Login">Login</a>
              </li>
              @else
                <li><a href="/profile" class="">Profile</a></li>
                <!-- <li>
                  <a class="register-link-grid" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                  </form>
                </li> -->
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
  <script type="text/javascript" src="{{ asset('js/moment.min.js')}}"></script>
    <script src="{{ asset('js/bootstrap.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js')}}"></script>
   <script type="text/javascript">
    $(function () {
      $('.sel').click(function(){
        $('.next_serv').show();
      })
      $('.ctn-btn').click(function(){
        activaTab($(this).data('tab'));
      });
    });
    function activaTab(tab){
        $('.nav-tabs a[href="#' + tab + '"]').tab('show');
        $('html, body').animate({
            scrollTop: $("#" + tab).offset().top - 300
        }, 500);
    };
    </script>
	</body>
</html>