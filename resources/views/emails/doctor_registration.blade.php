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
            @if ($recipientType === 'admin')
                New Doctor Registration
            @elseif ($recipientType === 'doctor')
                Registration Success
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
            <h2 style="text-align: center; color: #004a99;">Hi {{ $userData['name'] }},</h2>
            <p>Congratulations! Your registration process has been completed successfully.</p>
            <p>We are thrilled to have you on board. To get started, please log in to our system:</p>
            <p><a href="{{ url('/login') }}" class="button">Login Now</a></p>
        @endif

        <p>Thank you for joining our clinic. We look forward to working with you.</p>

        <p style="text-align: center;">Best regards,<br>The Clinic Team</p>
    </div>

    <div class="email-footer">
        <p style="color:white;">Contact Us | Privacy Policy</p>
    </div>
@endcomponent
