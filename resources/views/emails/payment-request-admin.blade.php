@include('emails.layouts.header')

<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Hi {{@$laundress->first_name}}</p>

<p style="color:#3d4852;font-size: 16px;line-height:1.5em;"> A laundress has requested to withdraw their payment.
</p>

@include('emails.layouts.footer')