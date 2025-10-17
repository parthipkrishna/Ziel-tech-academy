<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches'; // Table name

    protected $fillable = [
        'name',
        'address',
        'campus_id',
        'contact_number',
        'image',
        'google_map_link',
        'status',
    ];

    /**
     * Define relationship with the Campus model.
     */
    public function campus()
    {
        return $this->belongsTo(Campus::class, 'campus_id');
    }
}
