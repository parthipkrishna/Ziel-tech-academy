<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Influencer extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'name', 
        'email',
        'phone',
        'referral_code_id',
        'commission_per_user',
        'image',
        'kyc_docs',
    ];

    public function referralCode()
    {
        return $this->belongsTo(ReferralCode::class);
    }

    public function commissions()
    {
        return $this->hasMany(InfluencerCommission::class);
    }

    public function payments()
    {
        return $this->hasMany(ReferralPayment::class);
    }

    public function getTotalCommission()
    {
        return $this->commissions()->sum('amount');
    }

    public function getTotalWithdrawal()
    {
        return $this->payments()->sum('current_withdrawal');
    }

    public function getBalance()
    {
        return $this->getTotalCommission() - $this->getTotalWithdrawal();
    }

}