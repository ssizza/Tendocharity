<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CauseDonation extends Model
{
    use HasFactory;

    protected $table = 'cause_donations';
    
    protected $fillable = [
        'donor_id',
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
        'metadata',
        'ip_address',
        'user_agent',
        'browser',
        'os'
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

    // Relationships
    public function donor()
    {
        return $this->belongsTo(Donor::class, 'donor_id');
    }

    public function fundraiser()
    {
        // IMPORTANT: Check if your database has fundraiser_id referencing fundraisers table
        // If not, you might need to use campaigns table
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

    // Get formatted amount
    public function getFormattedAmountAttribute()
    {
        return $this->currency . ' ' . number_format($this->amount, 2);
    }
}