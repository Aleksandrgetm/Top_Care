<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paldies par pieteikumu</title>
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
                            <h1 style="margin:0;font-size:28px;line-height:34px;font-weight:700;">Paldies par Jūsu pieteikumu</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#ffffff;padding:32px;border-radius:0 0 28px 28px;">
                            <p style="margin:0 0 18px 0;font-size:18px;line-height:30px;color:#163329;">
                                Sveiki, {{ $data['name'] }}!
                            </p>
                            <p style="margin:0 0 24px 0;font-size:16px;line-height:28px;color:#4d6359;">
                                Paldies par Jūsu pieteikumu. Mēs esam saņēmuši informāciju un tuvākajā laikā ar Jums sazināsimies, lai precizētu detaļas un sagatavotu piemērotu piedāvājumu.
                            </p>

                            <div style="background-color:#f7faf7;border:1px solid #e2ece5;border-radius:22px;padding:22px 20px;">
                                <p style="margin:0 0 14px 0;font-size:13px;line-height:18px;letter-spacing:1.4px;text-transform:uppercase;color:#6d857a;">Jūsu pieteikuma informācija</p>
                                <p style="margin:0 0 10px 0;font-size:16px;line-height:26px;color:#355046;"><strong>Pakalpojums:</strong> {{ $data['service'] }}</p>
                                <p style="margin:0 0 10px 0;font-size:16px;line-height:26px;color:#355046;"><strong>Tālrunis:</strong> {{ $data['phone'] }}</p>
                                <p style="margin:0;font-size:16px;line-height:26px;color:#355046;white-space:pre-line;"><strong>Ziņojums:</strong> {{ $data['message'] }}</p>
                            </div>

                            <p style="margin:28px 0 0 0;font-size:16px;line-height:28px;color:#4d6359;">
                                Ar cieņu,<br>
                                <strong style="color:#163329;">Top Care Group</strong>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:22px 20px 0 20px;text-align:center;">
                            <p style="margin:0;font-size:13px;line-height:22px;color:#6d857a;">
                                Tālrunis: +371 28 842 265 • E-pasts: info@topcare.lv
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
