@extends('emails.layouts.topcare')

@php
    $deliveryPrice = $order->delivery_price !== null ? (float) $order->delivery_price : null;
    $subtotal = (float) $order->total_price;
    $finalTotal = $subtotal + ($deliveryPrice ?? 0.0);
    $deliveryMethodLabels = [
        'courier' => 'Kurjers',
        'dpd' => 'DPD',
        'omniva' => 'Omniva',
        'pickup' => 'Saņemšana uz vietas',
    ];
    $deliveryMethod = $deliveryMethodLabels[$order->delivery_method] ?? ($order->delivery_method ?: 'Nav norādīts');
@endphp

@section('email_title', 'Pasūtījums ir saņemts')
@section('email_heading', 'Pasūtījums #' . $order->id . ' ir saņemts')

@section('email_content')
    <p style="margin:0 0 18px 0;font-size:18px;line-height:30px;color:#163329;">
        Sveiki, {{ $order->customer_name }}!
    </p>
    <p style="margin:0 0 24px 0;font-size:16px;line-height:28px;color:#4d6359;">
        Paldies par Jūsu pasūtījumu. Mēs to esam saņēmuši un tuvākajā laikā ar klientu sazināsimies, lai apstiprinātu detaļas.
    </p>

    <div style="background-color:#f7faf7;border:1px solid #e2ece5;border-radius:22px;padding:22px 20px;">
        <p style="margin:0 0 14px 0;font-size:13px;line-height:18px;letter-spacing:1.4px;text-transform:uppercase;color:#6d857a;">Pasūtījuma informācija</p>
        <p style="margin:0 0 10px 0;font-size:16px;line-height:26px;color:#355046;"><strong>Pasūtījuma numurs:</strong> #{{ $order->id }}</p>
        <p style="margin:0 0 10px 0;font-size:16px;line-height:26px;color:#355046;"><strong>Piegādes veids:</strong> {{ $deliveryMethod }}</p>
        <p style="margin:0;font-size:16px;line-height:26px;color:#355046;"><strong>Kopā:</strong> €{{ number_format($finalTotal, 2, '.', ' ') }}</p>
    </div>

    <div style="margin-top:26px;background-color:#f7faf7;border:1px solid #e2ece5;border-radius:22px;padding:22px 20px;">
        <p style="margin:0 0 14px 0;font-size:13px;line-height:18px;letter-spacing:1.4px;text-transform:uppercase;color:#6d857a;">Preces</p>

        @foreach ($order->orderItems as $item)
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="{{ $loop->first ? '' : 'margin-top:14px;' }}">
                <tr>
                    <td style="padding:0 0 6px 0;font-size:16px;line-height:24px;font-weight:700;color:#163329;">
                        {{ $item->product_name }}
                    </td>
                    <td align="right" style="padding:0 0 6px 12px;font-size:16px;line-height:24px;font-weight:700;color:#163329;white-space:nowrap;">
                        €{{ number_format((float) $item->total, 2, '.', ' ') }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size:14px;line-height:22px;color:#4d6359;">
                        Daudzums: {{ $item->quantity }} × €{{ number_format((float) $item->price, 2, '.', ' ') }}
                    </td>
                </tr>
            </table>
        @endforeach
    </div>

    <p style="margin:28px 0 0 0;font-size:16px;line-height:28px;color:#4d6359;">
        Ar cieņu,<br>
        <strong style="color:#163329;">Top Care Group</strong>
    </p>
@endsection
