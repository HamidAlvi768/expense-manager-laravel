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
    <h1 style="color:white;">
        @if ($recipientType === 'admin')
            New Doctor Registration
        @elseif ($recipientType === 'doctor')
            Registration at Alshifa Dental Specialists
        @endif
    </h1>
</div>

<div class="email-body">
    @if ($recipientType === 'admin')
        <h2 style="text-align: center; color: #004a99;">New Doctor Registered</h2>
        <p>A new doctor, {{ $userData['name'] }}, has been registered in the system.</p>
        <p><strong>Doctor Email:</strong> {{ $userData['email'] }}</p>
        <p><strong>Registration Date:</strong> {{ now()->format('l, F j, Y') }}</p>
    @elseif ($recipientType === 'doctor')
        <p>Hi {{ $userData['name'] }},</p>
        <p>Your registration process has been completed successfully.</p>
        
    @endif

</div>

<div class="email-footer">

    <p style="color:white;">Alshifa Dental Specialists Team </p>
    <p style="color:white;">Contact Us on Mob: 0316 5854740 - Tel: 051 6131786 </p>
</div>
@endcomponent
