<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #2563eb;
            color: white;
            padding: 8px;
            text-align: left;
        }

        td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .status-pending {
            color: #d97706;
            font-weight: bold;
        }

        .status-proses {
            color: #2563eb;
            font-weight: bold;
        }

        .status-selesai {
            color: #16a34a;
            font-weight: bold;
        }

        .status-batal {
            color: #dc2626;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <h2>📊 Laporan Order UMKM</h2>
    <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Pelanggan</th>
                <th>WhatsApp</th>
                <th>Alamat</th>
                <th>Detail Order</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $i => $order)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $order->nama_pelanggan }}</td>
                <td>{{ $order->nomor_whatsapp }}</td>
                <td>{{ $order->alamat }}</td>
                <td>{{ $order->detail_order }}</td>
                <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                <td class="status-{{ $order->status }}">{{ ucfirst($order->status) }}</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Total Order: {{ count($orders) }} | UMKM Order System
    </div>
</body>

</html>