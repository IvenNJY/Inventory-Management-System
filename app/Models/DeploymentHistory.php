<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeploymentHistory extends Model
{
    protected $table = 'deployed_asset_history';
    
    public $timestamps = true;

    protected $fillable = [
        'deploy_asset_id',
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
        'asset_id' => 'string',
    ];

    public function deployedAsset()
    {
        return $this->belongsTo(DeployedAsset::class, 'deploy_asset_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function request()
    {
        return $this->belongsTo(RequestAsset::class, 'request_id');
    }
}