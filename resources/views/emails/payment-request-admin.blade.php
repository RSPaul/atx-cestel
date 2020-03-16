@include('emails.layouts.header')

<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Hi Admin,</p>

<p style="color:#3d4852;font-size: 16px;line-height:1.5em;"> {{@$laundress->first_name}} laundress has requested to withdraw their payment for ${{$price}}.
</p>

@include('emails.layouts.footer')