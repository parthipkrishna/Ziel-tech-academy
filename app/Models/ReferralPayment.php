<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralPayment extends Model
{

    protected $fillable = [
        'influencer_id', 
        'current_withdrawal', 
        'gst_number',
        'payment_date',
        'method', 
        'transaction_id', 
        'attachment_path', 
        'status', 
        'notes'
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function influencer()
    {
        return $this->belongsTo(Influencer::class);
    }

    public function getAttachmentUrlAttribute()
    {
        return $this->attachment_path ? asset('storage/' . $this->attachment_path) : null;
    }

    public static function paymentMethods()
    {
        return [
            'cash' => 'Cash',
            'upi' => 'Upi',
            'bank_transfer' => 'Bank Transfer',
            'cheque' => 'Cheque',
            'other' => 'Other',
        ];
    }

    public static function paymentStatus()
    {
        return [
            'initiated'    => 'Initiated',
            'processing'   => 'Processing',
            'completed'    => 'Completed',
            'failed'       => 'Failed',
            'rejected'     => 'Rejected',
            'check_issues' => 'Check Issues',
            'on_hold'      => 'On Hold',
        ];
    }

}