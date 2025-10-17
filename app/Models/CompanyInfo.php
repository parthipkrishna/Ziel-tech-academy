<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    use HasFactory;

    protected $table = 'company_infos'; // Table name

    protected $fillable = [
        'mission',
        'vision',
        'why_choose_us',
        'offerings',
    ];
}
