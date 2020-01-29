@extends('layouts.main')

@section('content')

<!-- banner -->
<section class="inner-page-banner login-inner-banner">
    <div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="page-title-wrap text-center">
					<h1 class="page-title-heading">Login</h1>
					<p class="page-sub-title">Submit Login Details</p>
				</div>
			</div>
		</div>
    </div>
</section>
<!-- //banner -->

<div class="content-wrap">
<section class="login-main-section">
	<div class="container">
		<!-- login  -->
		<div class="row">
			<div class="col-md-8 col-lg-6 offset-lg-3 offset-md-2">
				<div class="form-border-md">
				<form class="login-form-grid"  method="POST" action="{{ route('login') }}">
						@csrf
					<div class="form-group">
						<label class="col-form-label">Email</label>
						<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
						@if ($errors->has('email'))
							<span class="invalid-feedback" role="alert">
								<strong>{{ $errors->first('email') }}</strong>
							</span>
						@endif
					</div>
					<div class="form-group">
						<label class="col-form-label">Password</label>
						<input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
						@if ($errors->has('password'))
							<span class="invalid-feedback" role="alert">
								<strong>{{ $errors->first('password') }}</strong>
							</span>
						@endif
					</div>
					<button type="submit" class="btn btn-default more-btn btn-block">Login</button>
					<div class="row sub-w3l mt-3 mb-lg-3">
						<div class="col-sm-6 sub-w3layouts_hub">
							<input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
							<label for="brand1">
								<span></span>Remember me?</label>
						</div>
						<div class="col-sm-6 forgot-w3l text-sm-right">
							@if (Route::has('password.request'))
								<a class="text-li text-style-w3ls" href="{{ route('password.request') }}">
									{{ __('Forgot Your Password?') }}
								</a>
							@endif
						</div>
					</div>

					<p class="text-center dont-do">Don't have an account?
						<a href="/register" class="font-weight-bold">
							Register Now</a>
					</p>
				</form>
				</div>
			</div>
		</div>
		<!-- //login -->
	</div>
</section>
</div>
@endsection