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

    // Since your table uses created_at and updated_at (snake_case), 
    // you don't need to specify custom names here
    
    public function event()
    {
        return $this->belongsTo(Event::class, 'eventId');
    }

    public function getImagePath()
    {
        return asset('assets/images/events/gallery/' . $this->image_url);
    }
}