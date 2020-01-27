@extends('layouts.main')

@section('title', 'Details')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@endsection

<style type="text/css">
    td.day.disabled {
        opacity: 0.2;
    }
    .highlight {
      background: #eee;
    }
 

  .star-ratings {
    unicode-bidi: bidi-override;
    color: #c5c5c5;
    font-size: 34px;
    line-height: 1.2;
    width: 148px;
    margin: 0px;
    position: relative;
    padding: 0;
    text-align: center;
    display: inline-block;
    margin-top: 10px;
  }
  .star-ratings-top {
    color: gold;
    padding: 0;
    position: absolute;
    z-index: 1;
    display:block;
    left: 0px;
    overflow: hidden;
  }

  .star-ratings-bottom { 
    z-index: 0; 
  }
  
  .star-ratings-top[style] {
    left: 5px !important;
  }

  .modal-backdrop.fade.show 
  {
    /*display: none!important;*/
 }
<!--Neha--->
  .button.close {
    z-index: 9999999;
    position: relative;
}
<!--Neha--->
</style>

@section('content')
<!-- banner -->
<section class="inner-page-banner contact-page-banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-title-wrap text-center">
                    <h1 class="page-title-heading">{{ __('Reset Password') }}</h1>
                    <!--<p class="page-sub-title">For your Next Fine Dining Experience<br/> No Matter Where You Are</p>-->
                </div>
            </div>
        </div>
    </div>
</section>
<div class="content-wrap">
    <section class="pt-lg-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-10 offset-md-1 offset-lg-2">
                <!-- Single post section -->
                  @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('warning'))
                    <div class="alert alert-warning">
                        {{ session('warning') }}
                    </div>
                @endif


   

               <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Reset Password') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                                      


                <div class="clearfix"></div>
            </div>
        </div>
    </section>    
</div>


@section('scripts')

@endsection
@endsection
