<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CauseDonation extends Model
{
    use HasFactory;

    protected $table = 'cause_donations';
    
    protected $fillable = [
        'fundraiser_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'donor_address',
        'amount',
        'currency',
        'payment_method',
        'payment_status',
        'payment_reference',
        'is_anonymous',
        'message',
        'tax_deductible',
        'receipt_sent',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'tax_deductible' => 'boolean',
        'receipt_sent' => 'boolean',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // NOTE: Currently fundraiser_id references campaigns table
    // We might need to either:
    // 1. Change the foreign key to reference fundraisers table
    // 2. Or use campaigns table for donations
    
    // For now, I'll create a relationship assuming we fix the foreign key
    public function fundraiser()
    {
        return $this->belongsTo(Fundraiser::class, 'fundraiser_id');
    }

    // Scope for completed donations
    public function scopeCompleted($query)
    {
        return $query->where('payment_status', 'completed');
    }

    // Scope for pending donations
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    // Get donor display name (anonymous or actual)
    public function getDonorDisplayNameAttribute()
    {
        return $this->is_anonymous ? 'Anonymous' : $this->donor_name;
    }
}