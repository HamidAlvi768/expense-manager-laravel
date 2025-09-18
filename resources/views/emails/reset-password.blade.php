@component('mail::message')
# Password Reset Request

You requested to reset your password. Click the button below to reset it:

@component('mail::button', ['url' => $resetLink])
Reset Password
@endcomponent

If you did not request this, please ignore this email.

This password reset link will expire in **10 minutes**.

Thank you,  
{{ config('app.name') }}
@endcomponent
