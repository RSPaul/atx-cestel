@extends('layouts.main')
@section('styles')

@endsection

@section('title', 'Contact us')

@section('content')
<style>
.btn-primary{
	width:100%;
}
.reset_pass_form {
    margin-top: 5%;
}
.page-title-heading{
    float: left;
    margin-bottom: 25px;
    margin-top: 30px;
}
</style>
<!-- banner -->
<section class="inner-page-banner contact-page-banner reset_pass_form">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-title-wrap text-center">
                    <h3 class="page-title-heading">Reset Password</h3>
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

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group row {{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Send Password Reset Link') }}
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
