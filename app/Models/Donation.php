<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
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
        'metadata' => 'array'
    ];

    protected $appends = ['formatted_amount'];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    protected function formattedAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->currency . ' ' . number_format($this->amount, 2),
        );
    }

    public function scopeCompleted($query)
    {
        return $query->where('payment_status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function markAsCompleted($reference = null)
    {
        $this->payment_status = 'completed';
        if ($reference) {
            $this->payment_reference = $reference;
        }
        $this->save();
        
        // Update campaign funding
        $this->campaign->updateFunding($this->amount);
    }
}