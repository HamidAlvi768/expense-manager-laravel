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
        @if ($recipientType === 'patient')
            Appointment Reminder
        @elseif ($recipientType === 'doctor')
            Appointment Reminder
        @endif
    </h1>
</div>

<div class="email-body">
    @if ($recipientType === 'patient')
        <h2 style="text-align: center; color: #004a99;">Dear {{ $appointment->user->name }},</h2>
        <p>This is a reminder for your upcoming appointment with Dr. {{ $appointment->doctor->name }}.</p>
    @elseif ($recipientType === 'doctor')
        <h2 style="text-align: center; color: #004a99;">Dear Dr. {{ $appointment->doctor->name }},</h2>
        <p>This is a reminder for your upcoming appointment with {{ $appointment->user->name }}.</p>
    @endif
    <br>
    <p>Your appointment time (approx.): {{ $appointment->start_time }}</p>
    <br>
    <p>Your appointment date: {{ date('l jS F Y', strtotime($appointment->appointment_date)) }}</p>
    <br>
    @if ($appointment->problem)
    <p>Appointment description:<br>
    "{!! nl2br(str_replace(['script'], ['noscript'], $appointment->problem)) !!}"</p>
    @endif

    
    
    <p>Thank you for choosing Alshifa Dental Specialists. We look forward to serving you.</p>
    <p style="text-align: center;">Best regards,<br>Alshifa Dental Specialists Team</p>
</div>

@endcomponent
