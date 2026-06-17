<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('email_title')</title>
</head>
@php($emailLogoUrl = rtrim((string) config('app.url'), '/') . '/images/logo.png')
<body style="margin:0;padding:0;background-color:#f4f8f4;font-family:Arial,Helvetica,sans-serif;color:#163329;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f4f8f4;margin:0;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;margin:0 auto;">
                    <tr>
                        <td style="padding:0 20px 20px 20px;text-align:center;">
                            <img src="{{ $emailLogoUrl }}" alt="Top Care Group" style="max-width:180px;width:100%;height:auto;border:0;">
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#003f2d;border-radius:28px 28px 0 0;padding:28px 32px;color:#ffffff;">
                            <p style="margin:0 0 10px 0;font-size:12px;line-height:18px;letter-spacing:2px;text-transform:uppercase;color:#bfd730;">
                                @yield('email_eyebrow', 'Top Care Group')
                            </p>
                            <h1 style="margin:0;font-size:28px;line-height:34px;font-weight:700;">@yield('email_heading')</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#ffffff;padding:32px;border-radius:0 0 28px 28px;">
                            @hasSection('email_intro')
                                <div style="margin:0 0 24px 0;font-size:16px;line-height:26px;color:#4d6359;">
                                    @yield('email_intro')
                                </div>
                            @endif

                            @yield('email_content')
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:22px 20px 0 20px;text-align:center;">
                            <p style="margin:0;font-size:13px;line-height:22px;color:#6d857a;">
                                @yield('email_footer', 'Top Care Group • +371 28 842 265 • topcare.lv@gmail.com')
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
