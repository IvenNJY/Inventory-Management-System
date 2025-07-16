<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetsManagement extends Model
{
    use HasFactory;

    protected $table = 'asset_management';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'asset_name',
        'type',
        'serial_number',
        'model',
        'asset_tag',
        'purchase_date',
        'warranty_end_date',
        'expected_lifespan',
        'status',
    ];  

    // Use default timestamps (created_at, updated_at)
}
