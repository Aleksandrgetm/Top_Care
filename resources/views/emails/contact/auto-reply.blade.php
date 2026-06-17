@extends('emails.layouts.topcare')

@section('email_title', 'Paldies par pieteikumu')
@section('email_heading', 'Paldies par Jūsu pieteikumu')
@section('email_footer', 'Tālrunis: +371 28 842 265 • E-pasts: topcare.lv@gmail.com')

@section('email_content')
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
@endsection
