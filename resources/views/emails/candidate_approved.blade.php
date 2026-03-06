<!DOCTYPE html>
<html>
<head>
    <title>Candidate Application Approved</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-top: 5px solid #4CAF50;">
        <h2>Congratulations!</h2>
        <p>Dear {{ $candidateName }},</p>
        <p>We are pleased to inform you that your candidate application for the <strong>{{ $panchayatName }}</strong> Panchayat has been <strong>Approved</strong>.</p>
        
        <div style="background: #f4f4f4; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 0;"><strong>Your Candidate ID (Candidate Key):</strong></p>
            <h1 style="margin: 10px 0; color: #4CAF50; letter-spacing: 2px;">{{ $candidateId }}</h1>
        </div>

        <p>You can now log in to the system using your email and password to access the Candidate Dashboard. Please keep your Candidate ID safe as it may be required for official purposes during the election process.</p>
        
        <p>Best regards,<br>
        Election Management Team</p>
        
        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
        <p style="font-size: 0.8em; color: #777;">This is an automated message. Please do not reply to this email.</p>
    </div>
</body>
</html>
