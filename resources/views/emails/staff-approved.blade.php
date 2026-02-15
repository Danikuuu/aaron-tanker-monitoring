<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color: #333; padding: 24px;">
    <h2>Your Account Has Been Approved</h2>
    <p>Hi {{ $staff->first_name }},</p>
    <p>
        Your account has been approved by an administrator.
        You can now log in and access the system.
    </p>
    <p style="margin-top: 24px;">
        <a href="{{ route('login') }}"
           style="background:#2563eb; color:#fff; padding:10px 20px;
                  border-radius:6px; text-decoration:none;">
            Log In Now
        </a>
    </p>
    <p style="margin-top: 24px; font-size: 13px; color: #888;">
        If you did not register for this account, please ignore this email.
    </p>
</body>
</html>