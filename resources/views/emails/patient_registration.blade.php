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
        <h1>{{ $recipientType === 'admin' ? 'New Patient Registration' : 'Registration Successful' }}</h1>
    </div>

    <div class="email-body">
        @if ($recipientType === 'admin')
            <p style="font-family: Arial, sans-serif;">A new patient, {{ $userName }}, with MRN# {{ $mrnNumber }} has
                been registered.</p>
        @else
            <p style="font-family: Arial, sans-serif;">Hi {{ $userName }},</p>
            <p style="font-family: Arial, sans-serif;">Your registration process has been completed successfully. Please note
                down your MRN# {{ $mrnNumber }}.</p>
        @endif

        <p>Thank you for joining our clinic. We look forward to working with you.</p>

        <p style="text-align: center;">Best regards,<br>The Clinic Team</p>
    </div>

    <div class="email-footer">
        <p style="color:white;">Contact Us | Privacy Policy</p>
    </div>
@endcomponent
