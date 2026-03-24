<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Account Approved</title>
</head>
<body style="background-color: #F5F5F5; font-family: Arial, sans-serif; color: #111111; padding: 24px;">

    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 32px; text-align: center;">

        <h2 style="color: #FF3B3B; font-size: 24px; font-weight: 600; margin-bottom: 16px;">Congratulations!</h2>

        <p style="color: #6B7280; font-size: 16px; margin-bottom: 16px;">
            Hi {{ $staff->first_name }},
        </p>

        <p style="color: #6B7280; font-size: 16px; margin-bottom: 24px;">
            Your account has been approved by an administrator. You can now log in and access the system.
        </p>

        <a href="{{ route('login') }}" 
           style="display: inline-block; background-color: #2563EB; color: #ffffff; padding: 12px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; text-decoration: none; margin-bottom: 24px;">
            Log In Now
        </a>

        <p style="color: #6B7280; font-size: 13px; margin-top: 16px;">
            If you did not register for this account, please ignore this email.
        </p>

        <p style="color: #6B7280; font-size: 13px; margin-top: 24px;">
            &copy; {{ date('Y') }} AC Ang Fuel Distribution Services. All rights reserved.
        </p>

    </div>

</body>
</html>
