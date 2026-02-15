<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color: #333; padding: 24px;">
    <h2>New Staff Registration</h2>
    <p>A new staff member has registered and is awaiting approval:</p>
    <table style="border-collapse: collapse; width: 100%; max-width: 480px;">
        <tr>
            <td style="padding: 8px; font-weight: bold;">Name</td>
            <td style="padding: 8px;">{{ $staff->first_name }} {{ $staff->last_name }}</td>
        </tr>
        <tr style="background:#f9f9f9;">
            <td style="padding: 8px; font-weight: bold;">Email</td>
            <td style="padding: 8px;">{{ $staff->email }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight: bold;">Registered At</td>
            <td style="padding: 8px;">{{ $staff->created_at->format('M d, Y h:i A') }}</td>
        </tr>
    </table>
    <p style="margin-top: 24px;">
        Please log in to the admin panel to review and approve this account.
    </p>
</body>
</html>