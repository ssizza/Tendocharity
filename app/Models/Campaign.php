<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'title',
        'slug',
        'tagline',
        'description',
        'problem_statement',
        'solution_statement',
        'featured_image',
        'gallery_images',
        'funding_goal',
        'funding_raised',
        'currency',
        'donors_count',
        'start_date',
        'end_date',
        'urgency_level',
        'status',
        'location_country',
        'location_region',
        'location_coordinates',
        'impact_metrics',
        'beneficiaries_count',
        'progress_percentage',
        'updates_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'impact_metrics' => 'array',
        'funding_goal' => 'decimal:2',
        'funding_raised' => 'decimal:2',
        'progress_percentage' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'donors_count' => 'integer',
        'beneficiaries_count' => 'integer',
        'updates_count' => 'integer'
    ];

    protected $appends = [
        'image_url',
        'days_remaining',
        'funding_percentage',
        'is_urgent',
        'formatted_raised',
        'formatted_goal'
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function updates(): HasMany
    {
        return $this->hasMany(CampaignUpdate::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(CampaignMilestone::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(CampaignFaq::class);
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->featured_image 
                ? asset('storage/' . $this->featured_image) 
                : asset('images/default-campaign.jpg'),
        );
    }

    protected function daysRemaining(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->end_date) return null;
                $end = \Carbon\Carbon::parse($this->end_date);
                $now = \Carbon\Carbon::now();
                return max(0, $now->diffInDays($end, false));
            }
        );
    }

    protected function fundingPercentage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->funding_goal > 0 
                ? min(100, round(($this->funding_raised / $this->funding_goal) * 100, 2))
                : 0,
        );
    }

    protected function isUrgent(): Attribute
    {
        return Attribute::make(
            get: fn () => in_array($this->urgency_level, ['urgent', 'critical']),
        );
    }

    protected function formattedRaised(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->funding_raised, 2),
        );
    }

    protected function formattedGoal(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->funding_goal, 2),
        );
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUrgent($query)
    {
        return $query->whereIn('urgency_level', ['urgent', 'critical']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function updateFunding($amount)
    {
        $this->increment('funding_raised', $amount);
        $this->increment('donors_count');
        $this->progress_percentage = $this->funding_percentage;
        $this->save();
    }
}