@component('mail::message')
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .email-header {
        background-color: #b38f40;
        color: white;
        padding: 20px;
        text-align: center;
    }

    .email-body {
        background-color: white;
        color: #333;
        padding: 20px;
    }

    .email-footer {
        background-color: #b38f40;
        color: white;
        text-align: center;
        padding: 10px;
    }

    .button {
        background-color: #8d7132;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
    }

    .button:hover {
        background-color: #fff;
        color: #8d7132;
    }
</style>
<center><img src="{{ asset('assets/images/logo.png') }}" alt="Alshifa Dental Specialists" style="height: 100px;"></center>

<div class="email-header">
    <h1>{{ $recipientType === 'admin' ? 'New Patient Registration' : 'Registration Successful' }}</h1>
</div>

<div class="email-body">
    @if ($recipientType === 'admin')
        <p style="font-family: Arial, sans-serif;">A new patient, {{ $userName }}, with MRN# {{ $mrnNumber }} has been registered.</p>
    @else
        <p style="font-family: Arial, sans-serif;">Hi {{ $userName }},</p>
        <p style="font-family: Arial, sans-serif;">Your registration process has been completed successfully. Please note down your MRN# {{ $mrnNumber }}.</p>
        <p style="font-family: Arial, sans-serif;">Thank you for joining our clinic.</p>
        <p style="font-family: Arial, sans-serif;">Best Regards, Alshifa Dental Specialists Team</p>
    @endif

</div>

<div class="email-footer">
    <p style="color:white;">Contact Us on Mob: 0316 5854740 - Tel: 051 6131786 </p>
</div>
@endcomponent
