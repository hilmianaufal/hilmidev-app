<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $order->invoice_number }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #0f172a;
            font-size: 13px;
        }

        .header {
            background: #2563eb;
            color: white;
            padding: 24px;
            border-radius: 12px;
        }

        .brand {
            font-size: 28px;
            font-weight: bold;
        }

        .muted {
            color: #64748b;
        }

        .box {
            border: 1px solid #dbeafe;
            border-radius: 12px;
            padding: 18px;
            margin-top: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        th {
            background: #eff6ff;
            color: #1d4ed8;
            text-align: left;
            padding: 12px;
        }

        td {
            border-bottom: 1px solid #e2e8f0;
            padding: 12px;
        }

        .total {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: #dcfce7;
            color: #15803d;
            font-weight: bold;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="brand">HilmiDev</div>
        <div>Premium Web Studio & Source Code Marketplace</div>
    </div>

    <div class="box">
        <h2>Invoice</h2>

        <p><strong>No Invoice:</strong> {{ $order->invoice_number }}</p>
        <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
        <p><strong>Status Order:</strong> {{ strtoupper($order->status) }}</p>
        <p>
            <strong>Pembayaran:</strong>
            <span class="badge">{{ strtoupper($order->payment_status) }}</span>
        </p>
    </div>

    <div class="box">
        <h3>Data Client</h3>

        <p><strong>Nama:</strong> {{ $order->user->name }}</p>
        <p><strong>Email:</strong> {{ $order->user->email }}</p>
    </div>

    <div class="box">
        <h3>Produk Dibeli</h3>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total">
            Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}
        </p>
    </div>

    <p class="muted" style="margin-top: 24px;">
        Invoice ini dibuat otomatis oleh sistem HilmiDev.
    </p>
</body>
</html>