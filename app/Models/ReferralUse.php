<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class ReferralUse extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'referral_code_id', 'used_by_user_id', 'used_at', 'source', 'status', 'converted_at'
    ];

    public function referralCode()
    {
        return $this->belongsTo(ReferralCode::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'used_by_user_id');
    }

    public function commission()
    {
        return $this->hasOne(InfluencerCommission::class);
    }
}