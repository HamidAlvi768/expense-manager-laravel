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
    <h1 style="color:white;">Welcome to Our Dental Clinic</h1>
  </div>

  <div class="email-body">
    <center><img src="{{ asset('assets/images/logo.png') }}" alt="Clinic Logo" style="height: 100px;"></center>
    <h2 style="text-align: center; color: #004a99;">Dear {{ $name }},</h2>
    <p>Thank you for visiting our dental clinic. We are delighted to have you as our patient and are committed to providing you with the best dental care possible.</p>
    <p>Our clinic offers a wide range of services, including routine check-ups, cleanings, fillings, and more. Our team of experienced professionals is here to ensure that your visit is as comfortable and pleasant as possible.</p>
    <p>If you have any questions or need to schedule your next appointment, please don't hesitate to contact us at {{ $clinicPhone }} or email us at {{ $clinicEmail }}.</p>
    <p style="text-align: center;">We look forward to seeing you again soon!</p>
    <p style="text-align: center;">Sincerely,<br>{{ $clinicName }}</p>
  </div>

  <div class="email-footer">
    <p style="color:white;">Contact Us on Mob: 0316 5854740 - Tel: 051 6131786 </p>
  </div>
</section>

<!-- Bootstrap JS, if needed -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>