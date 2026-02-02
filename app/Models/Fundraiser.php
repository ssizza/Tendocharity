<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Fundraiser extends Model
{
    protected $fillable = [
        'service_id', 'category_id', 'title', 'tagline', 'slug',
        'short_description', 'description', 'problem_statement', 
        'solution_statement', 'featured_image', 'gallery_images', 
        'target_amount', 'raised_amount', 'currency', 'start_date', 
        'end_date', 'urgency_level', 'status', 'is_featured', 
        'priority', 'video_url', 'location', 'location_country',
        'location_region', 'latitude', 'longitude', 'impact_metrics',
        'beneficiaries_count', 'progress_percentage', 'updates_count',
        'project_leader', 'organization_name', 'organization_type',
        'beneficiaries', 'total_beneficiaries_target', 'risks_challenges',
        'sustainability_plan', 'project_scope', 'timeline',
        'meta_title', 'meta_description', 'meta_keywords', 'created_by', 
        'updated_by', 'approved_by', 'approved_at'
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'raised_amount' => 'decimal:2',
        'gallery_images' => 'array',
        'impact_metrics' => 'array',
        'timeline' => 'array',
        'is_featured' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = Str::slug($model->title);
        });

        static::updating(function ($model) {
            if ($model->isDirty('title')) {
                $model->slug = Str::slug($model->title);
            }
        });
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // We'll add these relationships later when we create the models
    // For now, keep them commented out to avoid errors
    
    /*
    public function faqs()
    {
        return $this->hasMany(CauseFaq::class);
    }

    public function milestones()
    {
        return $this->hasMany(CauseMilestone::class);
    }

    public function updates()
    {
        return $this->hasMany(CauseUpdate::class);
    }

    public function donations()
    {
        return $this->hasMany(CauseDonation::class);
    }
    */

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getProgressAttribute()
    {
        if ($this->target_amount == 0) {
            return 0;
        }
        return min(100, round(($this->raised_amount / $this->target_amount) * 100, 2));
    }

    public function getDaysRemainingAttribute()
    {
        if (!$this->end_date) {
            return null;
        }
        
        $now = now();
        $end = $this->end_date;
        
        return $now->diffInDays($end, false);
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && 
               ($this->end_date === null || $this->end_date > now());
    }

    public function getUrgencyColorAttribute()
    {
        return [
            'normal' => 'info',
            'urgent' => 'warning',
            'critical' => 'danger'
        ][$this->urgency_level] ?? 'info';
    }
}