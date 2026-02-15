<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color: #333; padding: 24px;">
    <h2>Your Account Has Been Deleted</h2>
    <p>Hi {{ $staff->first_name }},</p>
    <p>
        Your account has been permanently deleted from the system by an administrator.
        You will no longer be able to log in or access any resources.
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