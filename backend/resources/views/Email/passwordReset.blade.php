@component('mail::message')
# Change password Request

Click on the button below to change password

The body of your message.

@component('mail::button', ['url' => 'http://localhost:4200/response-reset?token='.$token])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
