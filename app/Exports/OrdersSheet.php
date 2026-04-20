<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersSheet implements FromCollection, WithHeadings, WithStyles, WithMapping
{
    protected int $row = 0;

    public function collection()
    {
        return Order::where('user_id', auth()->id())->latest()->get();
    }

    public function map($order): array
    {
        $this->row++;
        return [
            $this->row,
            $order->nama_pelanggan,
            $order->nomor_whatsapp,
            $order->alamat,
            $order->detail_order,
            'Rp ' . number_format($order->total_harga, 0, ',', '.'),
            ucfirst($order->status),
            $order->jam_input ?? '-',
            $order->created_at->format('d/m/Y'),
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pelanggan',
            'Nomor WhatsApp',
            'Alamat',
            'Detail Order',
            'Total Harga',
            'Status',
            'Jam Input',
            'Tanggal Order',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
