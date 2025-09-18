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
            Appointment Confirmation
        @elseif ($recipientType === 'doctor')
            New Appointment Notification
        @elseif ($recipientType === 'admin')
            New Appointment Added
        @endif
    </h1>
</div>

<div class="email-body">
    @if ($recipientType === 'patient')
        <h2 style="text-align: center; color: #004a99;">Dear {{ $appointment->user->name }},</h2>
        <p>We are pleased to confirm that your appointment with Dr. {{ $appointment->doctor->name }} at {{ date('l jS F Y', strtotime($appointment->appointment_date)) }} {{ $appointment->start_time }} has been successfully booked.</p>
        <!-- Appointment Details for Patient -->
    @elseif ($recipientType === 'doctor')
        <h2 style="text-align: center; color: #004a99;">Dear Dr. {{ $appointment->doctor->name }},</h2>
        <p>You have a new appointment scheduled with {{ $appointment->user->name }} at {{ date('l jS F Y', strtotime($appointment->appointment_date)) }} {{ $appointment->start_time }} .</p>
        <!-- Appointment Details for Doctor -->
    @elseif ($recipientType === 'admin')
        <h2 style="text-align: center; color: #004a99;">Admin Notification:</h2>
        <p>A new appointment has been created for patient {{ $appointment->user->name }} with Dr. {{ $appointment->doctor->name }} at {{ date('l jS F Y', strtotime($appointment->appointment_date)) }} {{ $appointment->start_time }} .</p>
        <!-- Appointment Details for Admin -->
    @endif
    <br>
    
    @if ($appointment->problem)
    <p>Appointment for:<br>
    "{!! nl2br(str_replace(["script"], ["noscript"], $appointment->problem)) !!}"<p>
    @endif

</div>

<div class="email-footer">
    <p style="color:white;">Contact Us on Mob: 0316 5854740 - Tel: 051 6131786 </p>
</div>
@endcomponent
