@include('emails.layouts.header')

<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Hi {{$user['first_name']}}</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">You are register succesfully.  </p>
@include('emails.layouts.footer')