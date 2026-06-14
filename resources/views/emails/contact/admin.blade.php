<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jauns pieteikums</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f8f4;font-family:Arial,Helvetica,sans-serif;color:#163329;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f4f8f4;margin:0;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;margin:0 auto;">
                    <tr>
                        <td style="padding:0 20px 20px 20px;text-align:center;">
                            <img src="{{ asset('images/logo.png') }}" alt="Top Care Group" style="max-width:180px;width:100%;height:auto;border:0;">
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#003f2d;border-radius:28px 28px 0 0;padding:28px 32px;color:#ffffff;">
                            <p style="margin:0 0 10px 0;font-size:12px;line-height:18px;letter-spacing:2px;text-transform:uppercase;color:#bfd730;">Top Care Group</p>
                            <h1 style="margin:0;font-size:28px;line-height:34px;font-weight:700;">Jauns pieteikums no mājaslapas</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#ffffff;padding:32px;border-radius:0 0 28px 28px;">
                            <p style="margin:0 0 24px 0;font-size:16px;line-height:26px;color:#4d6359;">
                                Saņemts jauns pieteikums no vietnes <strong>topcare.lv</strong>. Zemāk ir klienta iesniegtā informācija.
                            </p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;border-spacing:0 14px;">
                                <tr>
                                    <td style="background-color:#f7faf7;border:1px solid #e2ece5;border-radius:18px;padding:18px 20px;">
                                        <p style="margin:0 0 6px 0;font-size:12px;line-height:18px;letter-spacing:1.4px;text-transform:uppercase;color:#6d857a;">Vārds</p>
                                        <p style="margin:0;font-size:17px;line-height:26px;font-weight:700;color:#163329;">{{ $data['name'] }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color:#f7faf7;border:1px solid #e2ece5;border-radius:18px;padding:18px 20px;">
                                        <p style="margin:0 0 6px 0;font-size:12px;line-height:18px;letter-spacing:1.4px;text-transform:uppercase;color:#6d857a;">Tālrunis</p>
                                        <p style="margin:0;font-size:17px;line-height:26px;font-weight:700;color:#163329;">{{ $data['phone'] }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color:#f7faf7;border:1px solid #e2ece5;border-radius:18px;padding:18px 20px;">
                                        <p style="margin:0 0 6px 0;font-size:12px;line-height:18px;letter-spacing:1.4px;text-transform:uppercase;color:#6d857a;">E-pasts</p>
                                        <p style="margin:0;font-size:17px;line-height:26px;font-weight:700;color:#163329;">{{ $data['email'] ?: 'Nav norādīts' }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color:#f7faf7;border:1px solid #e2ece5;border-radius:18px;padding:18px 20px;">
                                        <p style="margin:0 0 6px 0;font-size:12px;line-height:18px;letter-spacing:1.4px;text-transform:uppercase;color:#6d857a;">Pakalpojums</p>
                                        <p style="margin:0;font-size:17px;line-height:26px;font-weight:700;color:#163329;">{{ $data['service'] }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color:#f7faf7;border:1px solid #e2ece5;border-radius:18px;padding:18px 20px;">
                                        <p style="margin:0 0 6px 0;font-size:12px;line-height:18px;letter-spacing:1.4px;text-transform:uppercase;color:#6d857a;">Ziņojums</p>
                                        <p style="margin:0;font-size:16px;line-height:28px;color:#355046;white-space:pre-line;">{{ $data['message'] }}</p>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:26px;">
                                <tr>
                                    <td style="width:50%;padding-right:8px;vertical-align:top;">
                                        <div style="background-color:#003f2d;border-radius:18px;padding:18px 20px;">
                                            <p style="margin:0 0 6px 0;font-size:12px;line-height:18px;letter-spacing:1.4px;text-transform:uppercase;color:#bfd730;">Datums</p>
                                            <p style="margin:0;font-size:15px;line-height:24px;color:#ffffff;">{{ $data['submitted_at']->format('d.m.Y H:i') }}</p>
                                        </div>
                                    </td>
                                    <td style="width:50%;padding-left:8px;vertical-align:top;">
                                        <div style="background-color:#003f2d;border-radius:18px;padding:18px 20px;">
                                            <p style="margin:0 0 6px 0;font-size:12px;line-height:18px;letter-spacing:1.4px;text-transform:uppercase;color:#bfd730;">Source</p>
                                            <p style="margin:0;font-size:15px;line-height:24px;color:#ffffff;">{{ $data['source_page'] }}</p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:22px 20px 0 20px;text-align:center;">
                            <p style="margin:0;font-size:13px;line-height:22px;color:#6d857a;">
                                Top Care Group • +371 28 842 265 • topcare.lv@gmail.com
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
