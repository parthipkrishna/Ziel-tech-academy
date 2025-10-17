<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMediaLink extends Model
{
    use HasFactory;

    protected $table = 'social_media_links'; // Table name

    protected $fillable = [
        'platform',
        'url',
    ];

    public static function getPlatformOptions()
    {
        return ['facebook', 'x', 'instagram', 'linkedin', 'youtube','pinterest'];
    }
}
