<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventApplicant extends Model
{
    use HasFactory;

    protected $table = 'event_applicants';
    
    protected $fillable = [
        'eventId',
        'ticketId',
        'name',
        'email',
        'phone'
    ];

    protected $casts = [
        'createdAt' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'eventId');
    }
}