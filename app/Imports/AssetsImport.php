<?php

namespace App\Imports;

use App\Models\AssetsManagement;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;

class AssetsImport implements OnEachRow, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    public $importedCount = 0;

    public function onRow(Row $row)
    {
        $r = $row->toArray();
        \Log::info('Importing asset row:', $r);
        if (empty($r['asset_name']) || empty($r['type']) || empty($r['serial_number']) || empty($r['model']) || empty($r['asset_tag']) || empty($r['purchase_date']) || empty($r['warranty_end_date']) || empty($r['expected_lifespan']) || empty($r['status'])) {
            \Log::warning('Skipped row due to missing required fields:', $r);
            return;
        }
        try {
            AssetsManagement::create([
                'asset_name' => $r['asset_name'],
                'type' => $r['type'],
                'serial_number' => $r['serial_number'],
                'model' => $r['model'],
                'asset_tag' => $r['asset_tag'],
                'purchase_date' => $r['purchase_date'],
                'warranty_end_date' => $r['warranty_end_date'],
                'expected_lifespan' => $r['expected_lifespan'],
                'status' => $r['status'],
            ]);
            $this->importedCount++;
            \Log::info('Asset imported successfully:', $r);
        } catch (\Exception $e) {
            \Log::error('Error importing asset row:', ['row' => $r, 'error' => $e->getMessage()]);
        }
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
