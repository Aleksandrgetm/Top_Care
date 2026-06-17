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
    $deliveryDestination = $order->delivery_point_name
        ? collect([
            $order->delivery_point_name,
            $order->delivery_point_address,
            $order->delivery_city,
            $order->delivery_postal_code,
        ])->filter()->implode(', ')
        : $order->delivery_address;
@endphp

@section('email_title', 'Jauns pasūtījums')
@section('email_heading', 'Jauns pasūtījums #' . $order->id)

@section('email_intro')
    <p style="margin:0;">
        Saņemts jauns pasūtījums no <strong>topcare.lv</strong>. Zemāk ir visa pasūtījuma informācija apstrādei.
    </p>
@endsection

@section('email_content')
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;border-spacing:0 14px;">
        <tr>
            <td style="background-color:#f7faf7;border:1px solid #e2ece5;border-radius:18px;padding:18px 20px;">
                <p style="margin:0 0 6px 0;font-size:12px;line-height:18px;letter-spacing:1.4px;text-transform:uppercase;color:#6d857a;">Pasūtījuma numurs</p>
                <p style="margin:0;font-size:17px;line-height:26px;font-weight:700;color:#163329;">#{{ $order->id }}</p>
            </td>
        </tr>
        <tr>
            <td style="background-color:#f7faf7;border:1px solid #e2ece5;border-radius:18px;padding:18px 20px;">
                <p style="margin:0 0 6px 0;font-size:12px;line-height:18px;letter-spacing:1.4px;text-transform:uppercase;color:#6d857a;">Klients</p>
                <p style="margin:0;font-size:17px;line-height:26px;font-weight:700;color:#163329;">{{ $order->customer_name }}</p>
            </td>
        </tr>
        <tr>
            <td style="background-color:#f7faf7;border:1px solid #e2ece5;border-radius:18px;padding:18px 20px;">
                <p style="margin:0 0 6px 0;font-size:12px;line-height:18px;letter-spacing:1.4px;text-transform:uppercase;color:#6d857a;">Tālrunis</p>
                <p style="margin:0;font-size:17px;line-height:26px;font-weight:700;color:#163329;">{{ $order->customer_phone }}</p>
            </td>
        </tr>
        <tr>
            <td style="background-color:#f7faf7;border:1px solid #e2ece5;border-radius:18px;padding:18px 20px;">
                <p style="margin:0 0 6px 0;font-size:12px;line-height:18px;letter-spacing:1.4px;text-transform:uppercase;color:#6d857a;">E-pasts</p>
                <p style="margin:0;font-size:17px;line-height:26px;font-weight:700;color:#163329;">{{ $order->customer_email }}</p>
            </td>
        </tr>
        <tr>
            <td style="background-color:#f7faf7;border:1px solid #e2ece5;border-radius:18px;padding:18px 20px;">
                <p style="margin:0 0 6px 0;font-size:12px;line-height:18px;letter-spacing:1.4px;text-transform:uppercase;color:#6d857a;">Piegādes veids</p>
                <p style="margin:0;font-size:17px;line-height:26px;font-weight:700;color:#163329;">{{ $deliveryMethod }}</p>
            </td>
        </tr>
        <tr>
            <td style="background-color:#f7faf7;border:1px solid #e2ece5;border-radius:18px;padding:18px 20px;">
                <p style="margin:0 0 6px 0;font-size:12px;line-height:18px;letter-spacing:1.4px;text-transform:uppercase;color:#6d857a;">Piegādes adrese / punkts</p>
                <p style="margin:0;font-size:16px;line-height:28px;color:#355046;">{{ $deliveryDestination ?: 'Nav norādīts' }}</p>
            </td>
        </tr>
    </table>

    <div style="margin-top:26px;background-color:#f7faf7;border:1px solid #e2ece5;border-radius:22px;padding:22px 20px;">
        <p style="margin:0 0 14px 0;font-size:13px;line-height:18px;letter-spacing:1.4px;text-transform:uppercase;color:#6d857a;">Pasūtītās preces</p>

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

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:26px;">
        <tr>
            <td style="width:50%;padding-right:8px;vertical-align:top;">
                <div style="background-color:#003f2d;border-radius:18px;padding:18px 20px;">
                    <p style="margin:0 0 6px 0;font-size:12px;line-height:18px;letter-spacing:1.4px;text-transform:uppercase;color:#bfd730;">Piegāde</p>
                    <p style="margin:0;font-size:15px;line-height:24px;color:#ffffff;">
                        {{ $deliveryPrice === null ? 'Tiks precizēta' : ((float) $deliveryPrice === 0.0 ? 'Bezmaksas' : '€' . number_format((float) $deliveryPrice, 2, '.', ' ')) }}
                    </p>
                </div>
            </td>
            <td style="width:50%;padding-left:8px;vertical-align:top;">
                <div style="background-color:#003f2d;border-radius:18px;padding:18px 20px;">
                    <p style="margin:0 0 6px 0;font-size:12px;line-height:18px;letter-spacing:1.4px;text-transform:uppercase;color:#bfd730;">Kopā</p>
                    <p style="margin:0;font-size:15px;line-height:24px;color:#ffffff;">€{{ number_format($finalTotal, 2, '.', ' ') }}</p>
                </div>
            </td>
        </tr>
    </table>

    @if (filled($order->comment))
        <div style="margin-top:26px;background-color:#f7faf7;border:1px solid #e2ece5;border-radius:22px;padding:22px 20px;">
            <p style="margin:0 0 10px 0;font-size:13px;line-height:18px;letter-spacing:1.4px;text-transform:uppercase;color:#6d857a;">Komentārs</p>
            <p style="margin:0;font-size:16px;line-height:28px;color:#355046;white-space:pre-line;">{{ $order->comment }}</p>
        </div>
    @endif
@endsection
