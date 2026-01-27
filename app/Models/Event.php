<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'startDate',
        'endDate',
        'location',
        'type',
        'status'
    ];

    protected $casts = [
        'description' => 'array',
        'startDate' => 'datetime',
        'endDate' => 'datetime',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    // Specify the custom column names for timestamps
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function applicants()
    {
        return $this->hasMany(EventApplicant::class, 'eventId');
    }

    public function gallery()
    {
        return $this->hasMany(EventGallery::class, 'eventId');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')
                    ->where('startDate', '>', Carbon::now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing')
                    ->where('startDate', '<=', Carbon::now())
                    ->where('endDate', '>=', Carbon::now());
    }

    public function isOpenForBooking()
    {
        return $this->status === 'upcoming' && $this->startDate > Carbon::now();
    }

    public function getImagePath()
    {
        return asset('assets/images/events/' . $this->image);
    }
}