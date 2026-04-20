<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\DeletionLog;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OrdersExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Laporan Order'    => new OrdersSheet(),
            'Log Penghapusan'  => new DeletionLogsSheet(),
        ];
    }
}
