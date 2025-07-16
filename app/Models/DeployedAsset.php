<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class DeployedAsset extends Model
{
    protected $table = 'deployed_asset';
    
    public $timestamps = true;

    protected $fillable = [
        'request_id',
        'asset_id',
        'user_id',
        'assigned_to',
        'serial_num',
        'category',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function request()
    {
        return $this->belongsTo(RequestAsset::class, 'request_id');
    }

    public function asset()
    {
        return $this->belongsTo(AssetsManagement::class, 'asset_id', 'id');
    }

    public static function initializeFromAssetManagement()
    {
        $activeAssets = AssetsManagement::where('status', 'Active')->get();
        if ($activeAssets->isEmpty()) {
            Log::warning('No active assets found in asset_management table');
        }
        foreach ($activeAssets as $asset) {
            if (!$asset->id || !$asset->serial_number) {
                Log::error('Invalid asset data', ['id' => $asset->id, 'serial_number' => $asset->serial_number]);
                continue;
            }
            self::firstOrCreate(
                ['asset_id' => $asset->id, 'serial_num' => $asset->serial_number],
                [
                    'request_id' => null,
                    'user_id' => null,
                    'assigned_to' => null,
                    'category' => $asset->type,
                    'status' => 'available',
                ]
            );
        }
    }
}