<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignMilestone extends Model
{
    use HasFactory;

    protected $table = 'campaign_milestones';

    protected $fillable = [
        'campaign_id',
        'title',
        'description',
        'target_amount',
        'completion_date',
        'status',
        'sort_order'
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'completion_date' => 'date',
        'sort_order' => 'integer'
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}