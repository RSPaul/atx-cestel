@include('emails.layouts.header')

<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Hi {{$data->first_name}},</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">You have successfully booked your laundrer. </p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Below are the booking details:</p>

<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Laundrer Name: {{$laundress->first_name}}, {{$laundress->last_name}}</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Type: {{$data->service_type}}</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Categories: {{implode(',',unserialize($data->service_categories))}}</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Beds: {{$data->service_beds}}</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Day: {{$data->service_day}}</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Time: {{$data->service_time}}</p>

<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Laundress: {{$data->service_laundress}}</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Package: {{$data->service_package}}</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Amount: {{$data->service_amount}}</p>

<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Job Details: {{$data->service_job_details}}</p>

<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Folding Details: {{$data->service_folding_details}}</p>

<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Hanging Details: {{$data->service_hanging_details}}</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Washing Details: {{$data->service_washing_details}}</p>
<p style="color:#3d4852;font-size: 16px;line-height:1.5em;">Service Description: {{$data->service_description}}</p>



@include('emails.layouts.footer')