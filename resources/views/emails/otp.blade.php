<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your OTP Code</title>
</head>
<body style="background-color: #F5F5F5; font-family: Arial, sans-serif; color: #111111; padding: 24px;">

    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 32px; text-align: center;">
        
        <h2 style="color: #FF3B3B; font-size: 24px; font-weight: 600; margin-bottom: 16px;">Hello!</h2>
        
        <p style="color: #6B7280; font-size: 16px; margin-bottom: 24px;">
            We received a request to log in to your account. Use the OTP below to complete your login:
        </p>

        <h1 style="color: #FF3B3B; font-size: 40px; font-weight: 700; margin-bottom: 24px;">
            {{ $otp }}
        </h1>

        <p style="color: #6B7280; font-size: 14px;">
            This OTP is valid for 5 minutes. Please do not share it with anyone.
        </p>

        <p style="color: #6B7280; font-size: 13px; margin-top: 24px;">
            &copy; {{ date('Y') }} AC Ang Fuel Distribution Services. All rights reserved.
        </p>

    </div>

</body>
</html>
