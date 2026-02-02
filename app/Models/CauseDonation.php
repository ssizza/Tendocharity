<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CauseDonation extends Model
{
    protected $table = 'cause_donations';
    
    protected $fillable = [
        'fundraiser_id', 'donor_name', 'donor_email', 'donor_phone',
        'donor_address', 'amount', 'currency', 'payment_method',
        'payment_status', 'payment_reference', 'is_anonymous',
        'message', 'tax_deductible', 'receipt_sent', 'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'tax_deductible' => 'boolean',
        'receipt_sent' => 'boolean',
        'metadata' => 'array'
    ];

    public function fundraiser()
    {
        return $this->belongsTo(Fundraiser::class);
    }
}