<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'type', 'related_id', 'short_description', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public static function getBannerTypes()
    {
        return [
            'course' => 'Course',
            'toolkit' => 'Toolkit',
        ];
    }

    public function toolkit()
    {
        return $this->belongsTo(ToolKit::class, 'related_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'related_id');
    }
}
