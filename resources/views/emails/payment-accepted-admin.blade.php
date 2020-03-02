@include('emails.header')
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Hi {{@$laundress->first_name}}</p>

<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">You have released your payment.</p>


@include('emails.footer')