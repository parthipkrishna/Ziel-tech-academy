<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RazorpayOrder extends Model
{
    protected $fillable = [
        'payment_id',
        'razorpay_order_id',
        'razorpay_payment_id',
        'signature',
        'currency',
        'receipt',
        'response_payload',
        'error_payload',
    ];

    protected $casts = [
        'response_payload' => 'array',
        'error_payload'    => 'array',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
