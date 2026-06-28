<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - For-ASmile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body
    style="margin: 0; padding: 0; font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f5; padding: 40px 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0"
                    style="max-width: 600px; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                    <tr>
                        <td style="padding: 50px 50px 30px; text-align: center;">
                            <img src="https://portocemad.pages.dev/fas-logo.png" alt="Logo For-ASmile"
                                style="width: 140px; height: auto; display: block; margin: 0 auto;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0 50px 50px;">
                            <p
                                style="margin: 0 0 25px; color: #666; font-size: 15px; line-height: 1.7; text-align: center;">
                                Kami menerima permintaan untuk melakukan reset password pada akun Anda.
                                Gunakan kode verifikasi (OTP) di bawah ini untuk mengatur ulang password pada aplikasi
                                mobile.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <div
                                            style="background: #f8f8f8; border: 2px dashed #004aad; border-radius: 8px; padding: 18px 40px; display: inline-block;">
                                            <span
                                                style="font-size: 32px; font-weight: 700; color: #004aad; letter-spacing: 6px;">
                                                {{ $code }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <p
                                style="margin: 20px 0 0; font-size: 13px; color: #999; line-height: 1.6; text-align: center;">
                                *Kode ini rahasia dan hanya berlaku selama 60 menit.*<br>
                                Jika Anda tidak merasa melakukan permintaan ini, abaikan saja email ini.
                            </p>

                            <div
                                style="margin-top: 40px; padding-top: 30px; border-top: 1px solid #e5e5e5; text-align: center;">
                                <p style="margin: 0; color: #666; font-size: 14px; line-height: 1.8;">
                                    Salam hangat,<br>
                                    <strong style="color: #333;">Tim {{ config('app.name') }}</strong>
                                </p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="background: #f8f8f8; padding: 30px 50px; text-align: center; border-top: 1px solid #e5e5e5;">
                            <p style="margin: 0; color: #999; font-size: 12px; line-height: 1.8;">
                                Aplikasi Donasi Online Terintegrasi<br>
                                © {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
