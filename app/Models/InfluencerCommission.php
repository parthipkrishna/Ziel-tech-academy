<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfluencerCommission extends Model
{
    protected $fillable = [
        'influencer_id', 'referral_use_id', 'amount', 'status', 'paid_at', 'notes'
    ];

    public function influencer()
    {
        return $this->belongsTo(Influencer::class);
    }

    public function referralUse()
    {
        return $this->belongsTo(ReferralUse::class);
    }
}