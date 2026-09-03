<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password reset code</title>
</head>
<body style="margin: 0; background: #f4f7fb; color: #172033; font-family: Arial, Helvetica, sans-serif;">
    <div style="display: none; max-height: 0; overflow: hidden; opacity: 0;">
        Your {{ config('app.name') }} password reset code is ready.
    </div>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background: #f4f7fb; padding: 36px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 560px; background: #ffffff; border: 1px solid #e3e8f0; border-radius: 12px; overflow: hidden;">
                    <tr>
                        <td style="background: #172033; padding: 28px 36px; color: #ffffff;">
                            <div style="font-size: 13px; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase; color: #9ed8cf;">{{ config('app.name') }}</div>
                            <h1 style="margin: 16px 0 0; font-size: 28px; line-height: 1.2; font-weight: 700;">Password reset</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 36px;">
                            <p style="margin: 0 0 16px; font-size: 16px; line-height: 1.6;">We received a request to reset your password.</p>
                            <p style="margin: 0 0 24px; font-size: 16px; line-height: 1.6;">Enter this one-time verification code to continue:</p>
                            <div style="margin: 0 0 24px; padding: 20px; background: #edf8f6; border: 1px solid #c5e9e2; border-radius: 8px; text-align: center;">
                                <div style="font-size: 12px; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase; color: #367a72;">Verification code</div>
                                <div style="margin-top: 10px; color: #172033; font-size: 34px; line-height: 1; font-weight: 700; letter-spacing: 8px;">{{ $otp }}</div>
                            </div>
                            <p style="margin: 0 0 8px; color: #536078; font-size: 14px; line-height: 1.6;"><strong style="color: #172033;">This code expires in 5 minutes.</strong> For your security, never share it with anyone.</p>
                            <p style="margin: 24px 0 0; padding-top: 24px; border-top: 1px solid #e8ecf2; color: #536078; font-size: 14px; line-height: 1.6;">If you did not request a password reset, you can safely ignore this email. Your password will remain unchanged.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px 36px; background: #f8fafc; color: #718096; font-size: 12px; line-height: 1.5;">This is an automated message from {{ config('app.name') }}. Please do not reply to this email.</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
