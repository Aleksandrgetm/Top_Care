@extends('emails.layouts.topcare')

@section('email_title', 'Jauns pieteikums')
@section('email_heading', 'Jauns pieteikums no mājaslapas')

@section('email_intro')
    <p style="margin:0;">
        Saņemts jauns pieteikums no vietnes <strong>topcare.lv</strong>. Zemāk ir klienta iesniegtā informācija.
    </p>
@endsection

@section('email_content')
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
@endsection
