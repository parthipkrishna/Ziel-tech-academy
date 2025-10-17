<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlacementSuccessStory extends Model
{
    use HasFactory;

    protected $table = 'placement_success_stories'; // Table name

    protected $fillable = [
        'alumni_id',
        'placement_id',
        'story',
        'position',
        'joined_date',
    ];

    /**
     * Define relationship with the User (Alumni).
     */
    public function alumni()
    {
        return $this->belongsTo(User::class, 'alumni_id');
    }

    /**
     * Define relationship with the Placement.
     */
    public function placement()
    {
        return $this->belongsTo(Placement::class, 'placement_id');
    }
}
