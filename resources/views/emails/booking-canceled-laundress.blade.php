@include('emails.layouts.header')

<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Hi {{$laundress->first_name}},</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">User has canceled the booking.</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Below are the booking details:</p>

<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Type: {{$booking->service_type}}</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Categories: {{implode(',',unserialize($booking->service_categories))}}</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;"><b>Service Quantity:</b>
<?php foreach(unserialize($booking->service_quantity) as $key => $value){
	if($value != ''){
		echo "<br />" . $key." :".$value."<br />";
	}
}
?>
</p>

<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Day: {{$booking->service_day}}</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Time: {{$booking->service_time}}</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Package: {{$booking->service_package}}</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Amount: ${{$booking->service_amount}}</p>

<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Job Details: {{$booking->service_job_details}}</p>

<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Folding Details: {{$booking->service_folding_details}}</p>

<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Hanging Details: {{$booking->service_hanging_details}}</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Washing Details: {{$booking->service_washing_details}}</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Description: {{$booking->service_description}}</p>



@include('emails.layouts.footer')