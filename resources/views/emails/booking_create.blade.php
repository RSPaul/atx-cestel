@include('emails.layouts.header')

<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Hi {{$booking->user_id}}</p>

@include('emails.layouts.footer')