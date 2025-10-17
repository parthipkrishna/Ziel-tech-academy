<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchChannel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'batch_id',
        'group_name',
        'admin_name',
        'admin_phone',
        'admin_id',
        'type',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Relationships
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public static function getChannelTypes()
    {
        return [
            'whatsapp' => 'WhatsApp',
            'telegram' => 'Telegram',
            'other' => 'Other',
        ];
    }

}
