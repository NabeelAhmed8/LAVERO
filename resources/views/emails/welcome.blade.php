<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to Maison LAVÉRO</title>
</head>
<body style="margin: 0; padding: 0; background-color: #090909; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #ededed;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #090909; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #121212; border: 1px solid #262626; border-radius: 4px; padding: 40px;">
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

                    <!-- Separator -->
                    <tr>
                        <td style="border-top: 1px solid #222222; padding-top: 24px;">
                            <p style="font-size: 14px; line-height: 1.6; color: #d4d4d4; margin: 0 0 16px 0;">
                                Dear {{ $user->name }},
                            </p>
                            <p style="font-size: 14px; line-height: 1.6; color: #a3a3a3; margin: 0 0 20px 0;">
                                It is our distinct pleasure to welcome you to the Maison LAVÉRO private client circle. Your account is now active, granting you privileged access to seasonal runway drops, numbered capsule releases, and bespoke salon fittings in Paris and Milan.
                            </p>
                            <p style="font-size: 14px; line-height: 1.6; color: #a3a3a3; margin: 0 0 30px 0;">
                                Your account is authenticated via encrypted JWT tokens and secure bcrypt cryptography, ensuring complete confidentiality.
                            </p>
                        </td>
                    </tr>

                    <!-- CTA Button -->
                    <tr>
                        <td align="center" style="padding-bottom: 30px;">
                            <a href="{{ url('/shop') }}" style="display: inline-block; background-color: #ffffff; color: #000000; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.25em; text-decoration: none; padding: 14px 28px; border-radius: 2px;">
                                Explore Autumn / Winter Collection
                            </a>
                        </td>
                    </tr>

                    <!-- Sign off -->
                    <tr>
                        <td style="border-top: 1px solid #222222; padding-top: 20px; font-size: 11px; color: #666666; text-align: center; text-transform: uppercase; letter-spacing: 0.2em;">
                            &copy; {{ date('Y') }} Maison LAVÉRO S.A. &middot; Rue Saint-Honoré, Paris
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
