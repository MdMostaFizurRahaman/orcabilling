
@component('mail::message')

<h2>Dear<?php
	if($newCompanyDetails){
		echo ', '.$newCompanyDetails['company_name'];
	}
	?>!</h2>

Welcome to {{ config('app.name') }} family.<br>
<br>
Thanks for registering your company.
This is to inform you that you just created your company details and your updated company details are as follows.

@component('mail::table')
| Property       	        | Value         		                |
| --------------------- |:-------------------------------------:|
| Company Name		    | {{$newCompanyDetails['company_name']}}    |
{{-- | Logo                  | <img width="70" height="40" style="vertical-align: middle;" src="{{$newCompanyDetails['getFirstMediaUrl']('logo')}}" alt="{{config('app.name')}}. ' logo'"> | --}}
| Phone		            | {{$newCompanyDetails['phone']}}           |
| City		            | {{$newCompanyDetails['city']}}            |
| Zip Code		        | {{$newCompanyDetails['zip_code']}}        |
| Country		        | {{$newCompanyDetails['country']}}         |
| Invoice Prefix		| {{$newCompanyDetails['invoice_prefix']}}  |
| Postal Address		| {{$newCompanyDetails['postal_address']}}  |
| Bank Details          | {{$newCompanyDetails['bank_details']}}    |
| Mail From Email       | {{$newCompanyDetails['mail_from_email']}} |
| Mail From Name        | {{$newCompanyDetails['mail_from_name']}}  |
@endcomponent

Please, keep this credentials saved somewhere safe to avoid any sort of data peeping.
We hope you will be enjoying the journey with us.

Thanks.<br>
{{ config('app.name') }}

@slot('header_logo')
<img width="70" height="40" style="vertical-align: middle;" src="{{$newCompanyDetails['getFirstMediaUrl']('logo')}}" alt="">
@endslot

@slot('footer')
        This mail is being sent only to you for your future assistance respecting your data right. To learn more please visit our
        <a href='{{route('company.privacy-policy')}}' title='Privacy Policy'>privacy policy</a> page.
@endslot

{{-- @slot('subcopy')
        This mail is being sent only to you for your future assistance respecting your data right. To learn more please visit our
        <a href='{{route('company.privacy-policy')}}' title='Privacy Policy'>privacy policy</a> page.
@endslot --}}


@endcomponent
