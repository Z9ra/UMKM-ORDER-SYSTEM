<?php

namespace App\Exports;

use App\Models\DeletionLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DeletionLogsSheet implements FromCollection, WithHeadings, WithStyles, WithMapping
{
    protected int $row = 0;

    public function collection()
    {
        return DeletionLog::where('user_id', auth()->id())->latest()->get();
    }

    public function map($log): array
    {
        $this->row++;
        return [
            $this->row,
            $log->nama_pelanggan,
            $log->nomor_whatsapp,
            $log->detail_order,
            ucfirst($log->status),
            $log->dihapus_oleh,
            $log->created_at->format('d/m/Y H:i'),
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pelanggan',
            'Nomor WhatsApp',
            'Detail Order',
            'Status',
            'Dihapus Oleh',
            'Waktu',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
