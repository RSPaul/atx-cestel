@include('emails.layouts.header')

<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Hi {{$user->first_name}}</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Verify your email by clicking the link below:  </p>
<p><a href="{{$link}}">Verify Email</a></p>
@include('emails.layouts.footer')