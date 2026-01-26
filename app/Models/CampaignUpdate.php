<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignUpdate extends Model
{
    use HasFactory;

    protected $table = 'campaign_updates';

    protected $fillable = [
        'campaign_id',
        'title',
        'content',
        'images',
        'type',
        'is_public',
        'created_by'
    ];

    protected $casts = [
        'images' => 'array',
        'is_public' => 'boolean'
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
}