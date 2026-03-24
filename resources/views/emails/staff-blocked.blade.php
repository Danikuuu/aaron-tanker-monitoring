<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Account Blocked</title>
</head>
<body style="background-color: #F5F5F5; font-family: Arial, sans-serif; color: #111111; padding: 24px;">

    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 32px; text-align: center;">

        <h2 style="color: #FF3B3B; font-size: 24px; font-weight: 600; margin-bottom: 16px;">Account Blocked</h2>

        <p style="color: #6B7280; font-size: 16px; margin-bottom: 16px;">
            Hi {{ $staff->first_name }},
        </p>

        <p style="color: #6B7280; font-size: 16px; margin-bottom: 16px;">
            Your account has been blocked by an administrator. You are no longer able to log in to the system.
        </p>

        <p style="color: #6B7280; font-size: 16px; margin-bottom: 24px;">
            If you believe this is a mistake, please contact your administrator for further assistance.
        </p>

        <p style="color: #6B7280; font-size: 13px; margin-top: 24px;">
            This is an automated message, please do not reply.
        </p>

        <p style="color: #6B7280; font-size: 13px; margin-top: 24px;">
            &copy; {{ date('Y') }} AC Ang Fuel Distribution Services. All rights reserved.
        </p>

    </div>

</body>
</html>
