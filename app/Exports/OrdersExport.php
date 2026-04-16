<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Order::select(
            'id',
            'nama_pelanggan',
            'nomor_whatsapp',
            'alamat',
            'detail_order',
            'total_harga',
            'status',
            'created_at'
        )->get();
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
