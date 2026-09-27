<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $order->id }} - CAPTAiN J</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f1f5f9;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .receipt-card {
            background: #ffffff;
            width: 320px;
            max-width: 100%;
            padding: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .dashed-line {
            border-bottom: 1px dashed #000;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        th,
        td {
            padding: 4px 0;
        }

        .no-print {
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .btn-print {
            background-color: #0d6efd;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            padding: 8px 22px;
            font-size: 0.92rem;
            font-weight: bold;
            font-family: 'Courier New', Courier, monospace;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(13, 110, 253, 0.25);
            transition: all 0.15s ease-in-out;
        }

        .btn-print:hover {
            background-color: #0b5ed7;
            transform: translateY(-1px);
        }

        .btn-back-orders {
            background-color: #198754;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            padding: 8px 18px;
            font-size: 0.92rem;
            font-weight: bold;
            font-family: 'Courier New', Courier, monospace;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(25, 135, 84, 0.25);
            transition: all 0.15s ease-in-out;
        }

        .btn-back-orders:hover {
            background-color: #157347;
            color: #ffffff;
            transform: translateY(-1px);
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .receipt-card {
                box-shadow: none;
                width: 100%;
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="receipt-card">
        @php $shop = \App\Models\Setting::values(); @endphp
        <div class="text-center">
            <h3 style="margin: 0;">{{ $shop['shop_name'] }}</h3>
            @if(!empty($shop['shop_tagline']))
                <p style="margin: 2px 0; font-size: 0.8rem;">{{ $shop['shop_tagline'] }}</p>
            @endif
            @if(!empty($shop['shop_address']))
                <p style="margin: 2px 0; font-size: 0.72rem;">{{ $shop['shop_address'] }}</p>
            @endif
            @if(!empty($shop['shop_contact']))
                <p style="margin: 2px 0; font-size: 0.72rem;">Tel: {{ $shop['shop_contact'] }}</p>
            @endif
            <p style="margin: 2px 0; font-size: 0.75rem;">Order #{{ $order->id }} |
                {{ $order->created_at->format('Y-m-d H:i') }}</p>
        </div>

        <div class="dashed-line"></div>

        <div style="font-size: 0.8rem;">
            <div>Customer: {{ $order->customer_name ?? 'Walk-in' }}</div>
            <div>Type: {{ $order->order_type }}</div>
            <div>Cashier: {{ $order->user->full_name ?? ($order->user->username ?? 'System') }}</div>
            <div>Payment: {{ strtoupper($order->payment_method) }}</div>
        </div>

        <div class="dashed-line"></div>

        <table>
            <thead>
                <tr style="border-bottom: 1px solid #000;">
                    <th style="text-align: left;">Item</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->inventory->name ?? 'Item' }}</td>
                        <td class="text-center">{{ $item->qty }}</td>
                        <td class="text-end">₱{{ number_format($item->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="dashed-line"></div>

        <table style="font-size: 0.9rem;">
            @if($order->takeout_fee > 0)
                <tr>
                    <td>Take-out Fee:</td>
                    <td class="text-end">₱{{ number_format($order->takeout_fee, 2) }}</td>
                </tr>
            @endif
            <tr class="fw-bold" style="font-size: 1rem;">
                <td>TOTAL:</td>
                <td class="text-end">₱{{ number_format($order->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Amount Paid:</td>
                <td class="text-end">₱{{ number_format($order->amount_paid, 2) }}</td>
            </tr>
            <tr>
                <td>Change Due:</td>
                <td class="text-end">₱{{ number_format($order->change_due, 2) }}</td>
            </tr>
        </table>

        <div class="dashed-line"></div>

        <div class="no-print">
            <button onclick="window.print()" class="btn-print">Print</button>
            <a href="{{ route('pos.index') }}" class="btn-back-orders">Back to Orders</a>
        </div>
    </div>

</body>

</html>