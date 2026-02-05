<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donor extends Model
{
    use HasFactory;

    protected $table = 'donors';
    
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'country',
        'city',
        'address',
        'postal_code',
        'is_anonymous',
        'receive_updates',
        'tax_deductible_eligible',
        'total_donations',
        'total_amount',
        'last_donation_at'
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'receive_updates' => 'boolean',
        'tax_deductible_eligible' => 'boolean',
        'total_amount' => 'decimal:2',
        'last_donation_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function donations()
    {
        return $this->hasMany(CauseDonation::class, 'donor_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('receive_updates', true);
    }

    public function scopeWithDonations($query)
    {
        return $query->where('total_donations', '>', 0);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('last_donation_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getDisplayNameAttribute()
    {
        return $this->is_anonymous ? 'Anonymous' : $this->full_name;
    }

    public function getLocationAttribute()
    {
        if ($this->city && $this->country) {
            return $this->city . ', ' . $this->country;
        }
        return $this->country ?? $this->city ?? 'Unknown';
    }

    // Methods
    public function totalDonated($currency = 'USD')
    {
        return $this->donations()
            ->where('currency', $currency)
            ->where('payment_status', 'completed')
            ->sum('amount');
    }

    public function donationCount()
    {
        return $this->donations()
            ->where('payment_status', 'completed')
            ->count();
    }

    public function firstDonationDate()
    {
        return $this->donations()
            ->where('payment_status', 'completed')
            ->orderBy('created_at', 'asc')
            ->first()
            ->created_at ?? null;
    }

    public function updateDonationStats()
    {
        $this->total_donations = $this->donations()
            ->where('payment_status', 'completed')
            ->count();
        
        $this->total_amount = $this->donations()
            ->where('payment_status', 'completed')
            ->sum('amount');
        
        $this->last_donation_at = $this->donations()
            ->where('payment_status', 'completed')
            ->latest()
            ->first()
            ->created_at ?? null;
            
        $this->save();
    }
}