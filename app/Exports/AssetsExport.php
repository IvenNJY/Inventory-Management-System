<?php

namespace App\Exports;

use App\Models\AssetsManagement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return AssetsManagement::all([
            'asset_name',
            'type',
            'serial_number',
            'model',
            'asset_tag',
            'purchase_date',
            'warranty_end_date',
            'expected_lifespan',
            'status',
        ]);
    }

    public function headings(): array
    {
        return [
            'Asset Name',
            'Type',
            'Serial Number',
            'Model',
            'Asset Tag',
            'Purchase Date',
            'Warranty End Date',
            'Expected Lifespan',
            'Status',
        ];
    }
}
