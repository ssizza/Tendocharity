<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignFaq extends Model
{
    use HasFactory;

    protected $table = 'campaign_faqs';

    protected $fillable = [
        'campaign_id',
        'question',
        'answer',
        'sort_order'
    ];

    protected $casts = [
        'sort_order' => 'integer'
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}