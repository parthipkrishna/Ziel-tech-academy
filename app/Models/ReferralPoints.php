<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReferralPoints extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'referral_use_id',
        'type',
        'points',
        'source',
        'notes',
    ];

    /**
     * Student who earned the points
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Referral usage linked to these points
     */
    public function referralUse()
    {
        return $this->belongsTo(ReferralUse::class, 'referral_use_id');
    }

    /**
     * Total points earned by a student
     */
    public static function totalEarned($studentId)
    {
        return self::where('student_id', $studentId)
            ->where('type', 'earned')
            ->sum('points');
    }

    /**
     * Total points redeemed/used by a student
     */
    public static function totalRedeemed($studentId)
    {
        return self::where('student_id', $studentId)
            ->where('type', 'redeemed')
            ->sum('points');
    }

    /**
     * Total available points for a student
     */
    public static function availablePoints($studentId)
    {
        return self::totalEarned($studentId) - self::totalRedeemed($studentId);
    }

    /**
     * Scope: filter earned points
     */
    public function scopeEarned($query)
    {
        return $query->where('type', 'earned');
    }

    /**
     * Scope: filter redeemed points
     */
    public function scopeRedeemed($query)
    {
        return $query->where('type', 'redeemed');
    }

    /**
     * Scope: filter by student
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }
}
