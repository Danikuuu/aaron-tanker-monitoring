<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color: #333; padding: 24px;">
    <h2>Your Account Has Been Blocked</h2>
    <p>Hi {{ $staff->first_name }},</p>
    <p>
        Your account has been blocked by an administrator and you are
        no longer able to log in to the system.
    </p>
    <p>
        If you believe this is a mistake, please contact your administrator
        for further assistance.
    </p>
    <p style="margin-top: 24px; font-size: 13px; color: #888;">
        This is an automated message, please do not reply.
    </p>
</body>
</html>