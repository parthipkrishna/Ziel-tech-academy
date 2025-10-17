<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventMedia extends Model
{
    use HasFactory;

    protected $table = 'event_media'; // Define the table name (optional if it follows Laravel's naming convention)

    protected $fillable = [
        'event_id',
        'media_url',
        'type',
    ]; // Mass assignable attributes

    protected $casts = [
        'type' => 'string', // Casting the 'type' attribute as string
    ];

    /**
     * Relationship: Each media belongs to one event.
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public static function getMediaOptions()
    {
        return ['image', 'video', 'youtube', 'event'];
    }

}
