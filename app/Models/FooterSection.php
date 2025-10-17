<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterSection extends Model
{
    use HasFactory;

    protected $table = 'footer_sections';

    protected $fillable = [
        'title',
        'short_desc',
        'copy_right',
        'playstore',
        'appstore',
        'footer_logo',
        'slug',
    ];
}
