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
        @if ($recipientType === 'doctor')
            Lab Report Completed
        @elseif ($recipientType === 'admin')
            New Lab Report Notification
        @endif
    </h1>
</div>

<div class="email-body">
    @if ($recipientType === 'doctor')
        <h2 style="text-align: center; color: #004a99;">Dear Dr. {{ $doctorName }},</h2>
        <p>Your Lab report has been completed successfully and is now available.</p>
    @elseif ($recipientType === 'admin')
        <h2 style="text-align: center; color: #004a99;">Admin Notification:</h2>
        <p>We would like to inform you that a new lab report has been created and is now available for review. Please take a moment to check the details and ensure that everything is in order.</p>
    @endif

    <p>Thank you for your attention.</p>
    <p style="text-align: center;">Best regards,<br>The Lab Report Team</p>
</div>

<div class="email-footer">
    <p style="color:white;">Contact Us on Mob: 0316 5854740 - Tel: 051 6131786 </p>
</div>
@endcomponent
