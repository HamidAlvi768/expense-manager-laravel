@component('mail::message')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
</head>
<body>
<section>
  <div class="email-header">
    <h1 style="color:white;">Registration Confirmation</h1>
  </div>

  <div class="email-body">
    <center><img src="{{ asset('assets/images/logo.png') }}" alt="Alshifa Dental Specialists" style="height: 100px;"></center>
    <h2 style="text-align: center; color: #004a99;">Dear {{ $user->name }},</h2>
    <p>Welcome! You have successfully registered in our system.</p>
    
    <p><strong>Registration Details:</strong></p>
    <ul>
      <li>Email: {{ $user->email }}</li>
      <li>Password: {{ $password }}</li>
    </ul>

    <p>You can log in to your account here: <a href="{{ url('/') }}" class="button">Log In</a></p>
    
    <p>Thank you for joining us. If you have any questions or need assistance, feel free to reach out.</p>
    <p style="text-align: center;">Best regards,<br>The Alshifa Dental Specialists Team</p>
  </div>

  <div class="email-footer">
    <p style="color:white;">Contact Us on Mob: 0316 5854740 - Tel: 051 6131786 </p>
  </div>
</section>

<!-- Bootstrap JS, if needed -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

@endcomponent
