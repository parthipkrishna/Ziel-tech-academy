<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolKit extends Model
{
    protected $fillable = [
        'course_id',
        'name',
        'description',
        'short_description',
        'is_enabled',
        'price',
        'offer_price',
        'min_loyalty_points',
        'loyalty_points_earn'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function media()
    {
        return $this->hasMany(ToolKitMedia::class);
    }

    /**
     * Single enquiry related to this toolkit
     */
    public function enquiries()
    {
        return $this->hasMany(ToolKitEnquiry::class, 'toolkit_id');
    }
}
