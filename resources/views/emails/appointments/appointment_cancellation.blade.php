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
    <center><img
            src="https://cdn-ilbhjfj.nitrocdn.com/tWlnNeDrvTcEghwgWXwCyBsLoOFgOqMO/assets/images/optimized/rev-6462fab/alshifadentalspecialists.com/wp-content/uploads/elementor/thumbs/Latest-Clinic-Logo-qtindyj5rafj59ywqd6anrl1n0nkupk0wxsju53fuo.png"
            alt="Hospital Logo" style="height: 100px;"></center>
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
            <p>We regret to inform you that your appointment with Dr. {{ $appointment->doctor->name }} has been cancelled.
            </p>
            <p>If you have any questions or wish to reschedule, please contact us at your earliest convenience.</p>
        @elseif ($recipientType === 'doctor')
            <h2 style="text-align: center; color: #004a99;">Dear Dr. {{ $appointment->doctor->name }},</h2>
            <p>We would like to notify you that the appointment scheduled with patient {{ $appointment->user->name }} has
                been cancelled.</p>
            <p>If you have any concerns, please reach out to the administration.</p>
        @elseif ($recipientType === 'admin')
            <h2 style="text-align: center; color: #004a99;">Admin Notification:</h2>
            <p>An appointment for patient {{ $appointment->user->name }} with Dr. {{ $appointment->doctor->name }} has been
                cancelled.</p>
            <p>Please review the appointment details and take any necessary action.</p>
        @endif
        <br>
        <p>Your appointment time (approx.): {{ $appointment->start_time }} - {{ $appointment->end_time }}/
        <p><br>
        <p>Your appointment date: {{ date('l jS F Y', strtotime($appointment->appointment_date)) }}</p><br>
        @if ($appointment->problem)
            <p>Appointment reason:<br>
                "{!! nl2br(str_replace(['script'], ['noscript'], $appointment->problem)) !!}"
            <p>
        @endif

        <p>To view or manage the appointment, please log in to our website:
            <a href="{{ url('/') }}" class="button">View Appointment</a>
        </p>

<<<<<<< HEAD
    <p>To view or manage the appointment, please log in to our website: 
        <a href="{{ url('/') }}" class="button">View Appointment</a>
    </p>
    
    <p>Thank you for choosing Alshifa Dental Specialists. We look forward to serving you.</p>
    <p style="text-align: center;">Best regards,<br>The Alshifa Dental Specialists Team</p>
</div>

<div class="email-footer">
    <p style="color:white;">Contact Us on Mob: 0316 5854740 - Tel: 051 6131786 </p>
</div>
=======
        <p>Thank you for choosing Al Shifa Dental Hospital. We look forward to serving you.</p>
        <p style="text-align: center;">Best regards,<br>The Al Shifa Dental Hospital Team</p>
    </div>

    <div class="email-footer">
        <p style="color:white;">Contact Us | Privacy Policy</p>
    </div>
>>>>>>> 2802daa7cb47c817ee82f747599b601fadbe2f17
@endcomponent
