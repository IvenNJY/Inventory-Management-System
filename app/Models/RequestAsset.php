<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestAsset extends Model
{
    protected $table = 'request_assest';
    
    protected $fillable = [
        'asset_name',
        'type',
        'model_serial_num',
        'assigned_date',
        'status',
        'user_id',
        'asset_id',
        'reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}