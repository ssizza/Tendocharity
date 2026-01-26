<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventGallery extends Model
{
    use HasFactory;

    protected $table = 'event_gallery';
    
    protected $fillable = [
        'id',
        'image_url',
        'alt',
        'eventId'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'eventId');
    }

    public function getImagePath()
    {
        return asset('assets/images/events/gallery/' . $this->image_url);
    }
}