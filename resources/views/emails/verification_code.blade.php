<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Maison LAVÉRO Email Verification</title>
</head>
<body style="margin: 0; padding: 0; background-color: #090909; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #ededed;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #090909; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="560" cellpadding="0" cellspacing="0" style="background-color: #121212; border: 1px solid #262626; border-radius: 4px; padding: 40px;">
                    <!-- Monogram -->
                    <tr>
                        <td align="center" style="padding-bottom: 24px;">
                            <h1 style="margin: 0; font-family: Georgia, serif; font-size: 28px; letter-spacing: 0.35em; color: #ffffff; text-transform: uppercase;">
                                LAVÉRO
                            </h1>
                            <p style="margin: 6px 0 0 0; font-size: 9px; letter-spacing: 0.4em; color: #c5a880; text-transform: uppercase;">
                                Paris &middot; Milano
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="border-top: 1px solid #222222; padding-top: 24px;">
                            <p style="font-size: 14px; line-height: 1.6; color: #d4d4d4; margin: 0 0 16px 0;">
                                Hello {{ $user->name }},
                            </p>
                            <p style="font-size: 14px; line-height: 1.6; color: #a3a3a3; margin: 0 0 24px 0;">
                                Thank you for requesting private client membership with Maison LAVÉRO. To complete your registration and activate your secure access dossier, enter the following single-use verification code:
                            </p>
                            
                            <!-- Verification Code Box -->
                            <div style="background-color: #1a1a1a; border: 1px solid #c5a880; border-radius: 4px; padding: 18px; text-align: center; margin-bottom: 24px;">
                                <span style="font-family: monospace; font-size: 32px; letter-spacing: 0.4em; color: #ffffff; font-weight: bold;">
                                    {{ $code }}
                                </span>
                            </div>

                            <p style="font-size: 12px; line-height: 1.6; color: #737373; margin: 0 0 20px 0;">
                                This code is valid for 15 minutes. If you did not request this registration, please disregard this transmission.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="border-top: 1px solid #222222; padding-top: 20px; font-size: 11px; color: #666666; text-align: center; text-transform: uppercase; letter-spacing: 0.2em;">
                            &copy; {{ date('Y') }} Maison LAVÉRO S.A. &middot; Confidential Client Registry
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
