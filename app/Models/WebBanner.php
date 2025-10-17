<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebBanner extends Model
{
    use HasFactory;

    protected $table = 'web_banners'; // Table name

    protected $fillable = [
        'title',
        'image_url',
        'type',
        'short_desc',
        'description',
    ];

    public static function getTypes()
    {
        return [
            'campus', 'about us','placement', 'contact us', 'branches'
        ];
    }
}
