<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'batch_id',
        'joined_date',
        'age',
        'gender',
        'qualifications',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function batchUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'batch_tutor', 'tutor_id', 'batch_id')->withTimestamps();
    }

    public function tutorBatchesRelation()
    {
        return $this->hasMany(Batch::class, 'tutor_id', 'id');
    }

}
