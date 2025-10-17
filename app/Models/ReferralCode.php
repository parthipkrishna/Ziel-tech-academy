<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class ReferralCode extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'code', 'generated_by', 'type', 'deeplink_url', 'is_active'
    ];

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function uses()
    {
        return $this->hasMany(ReferralUse::class);
    }

    public function influencer()
    {
        return $this->hasOne(Influencer::class, 'referral_code_id');
    }
}