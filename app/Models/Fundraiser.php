<?php
// app/Models/Fundraiser.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fundraiser extends Model
{
    use HasFactory;

    protected $table = 'fundraisers';
    
    protected $fillable = [
        'service_id',
        'category_id',
        'title',
        'tagline',
        'slug',
        'short_description',
        'description',
        'problem_statement',
        'solution_statement',
        'featured_image',
        'gallery_images',
        'target_amount',
        'raised_amount',
        'currency',
        'start_date',
        'end_date',
        'urgency_level',
        'status',
        'is_featured',
        'priority',
        'video_url',
        'location',
        'location_country',
        'location_region',
        'latitude',
        'longitude',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'created_by',
        'updated_by',
        'approved_by',
        'approved_at',
        'impact_metrics',
        'beneficiaries_count',
        'progress_percentage',
        'updates_count',
        'project_leader',
        'organization_name',
        'organization_type',
        'beneficiaries',
        'total_beneficiaries_target',
        'risks_challenges',
        'sustainability_plan',
        'project_scope',
        'timeline'
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'impact_metrics' => 'array',
        'timeline' => 'array',
        'is_featured' => 'boolean',
        'target_amount' => 'decimal:2',
        'raised_amount' => 'decimal:2',
        'progress_percentage' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Accessor for featured image URL
    protected function featuredImageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->featured_image) {
                    return asset('assets/images/default.png');
                }
                
                if (str_starts_with($this->featured_image, 'assets/') || str_starts_with($this->featured_image, '/assets/')) {
                    return asset($this->featured_image);
                }
                
                if (str_starts_with($this->featured_image, 'storage/')) {
                    return asset($this->featured_image);
                }
                
                if (!str_contains($this->featured_image, '/')) {
                    return asset('assets/images/fundraisers/' . $this->featured_image);
                }
                
                return asset($this->featured_image);
            }
        );
    }

    // Accessor for gallery images URLs
    protected function galleryImagesUrls(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->gallery_images) {
                    return [];
                }
                
                $urls = [];
                $images = is_array($this->gallery_images) ? $this->gallery_images : json_decode($this->gallery_images, true);
                
                foreach ($images as $image) {
                    if (str_starts_with($image, 'assets/') || str_starts_with($image, '/assets/')) {
                        $urls[] = asset($image);
                    } elseif (str_starts_with($image, 'storage/')) {
                        $urls[] = asset($image);
                    } elseif (!str_contains($image, '/')) {
                        $urls[] = asset('assets/images/fundraisers/gallery/' . $image);
                    } else {
                        $urls[] = asset($image);
                    }
                }
                
                return $urls;
            }
        );
    }

    // Accessor for days remaining
    protected function daysRemaining(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->end_date) {
                    return null;
                }
                
                $end = \Carbon\Carbon::parse($this->end_date);
                $now = \Carbon\Carbon::now();
                
                if ($now->gt($end)) {
                    return 0;
                }
                
                return $now->diffInDays($end);
            }
        );
    }

    // Scope for active fundraisers
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for featured fundraisers
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Scope for upcoming fundraisers
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    // Scope for ongoing fundraisers
    public function scopeOngoing($query)
    {
        return $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    // Relationship with category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship with service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Relationship with donations (cause_donations table)
    public function donations()
    {
        return $this->hasMany(CauseDonation::class, 'fundraiser_id');
    }

    // Relationship with updates (cause_updates table)
    public function updates()
    {
        return $this->hasMany(CauseUpdate::class, 'fundraiser_id');
    }

    // Relationship with milestones (cause_milestones table)
    public function milestones()
    {
        return $this->hasMany(CauseMilestone::class, 'fundraiser_id');
    }

    // Relationship with FAQs (cause_faqs table)
    public function faqs()
    {
        return $this->hasMany(CauseFaq::class, 'fundraiser_id');
    }

    // Relationship with creator
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship with approver
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Helper method to get gallery image URL
    public function getGalleryImageUrl($imagePath)
    {
        if (!$imagePath) {
            return null;
        }
        
        if (str_starts_with($imagePath, 'assets/') || str_starts_with($imagePath, '/assets/')) {
            return asset($imagePath);
        }
        
        if (str_starts_with($imagePath, 'storage/')) {
            return asset($imagePath);
        }
        
        if (!str_contains($imagePath, '/')) {
            return asset('assets/images/fundraisers/gallery/' . $imagePath);
        }
        
        return asset($imagePath);
    }

    // Get all gallery image URLs
    public function getGalleryImageUrls()
    {
        if (!$this->gallery_images) {
            return [];
        }
        
        $urls = [];
        $images = is_array($this->gallery_images) ? $this->gallery_images : json_decode($this->gallery_images, true);
        
        foreach ($images as $image) {
            $urls[] = $this->getGalleryImageUrl($image);
        }
        
        return $urls;
    }

    // Count donors (from donations table)
    public function getDonorsCountAttribute()
    {
        return $this->donations()->count();
    }

    // Get successful donations count
    public function successfulDonationsCount()
    {
        return $this->donations()->where('payment_status', 'completed')->count();
    }

    // Check if has gallery images
    public function getHasGalleryImagesAttribute()
    {
        if (!$this->gallery_images) {
            return false;
        }
        
        $images = is_array($this->gallery_images) ? $this->gallery_images : json_decode($this->gallery_images, true);
        return is_array($images) && count($images) > 0;
    }

    // Get gallery images as array
    public function getGalleryImagesArrayAttribute()
    {
        if (!$this->gallery_images) {
            return [];
        }
        
        $images = is_array($this->gallery_images) ? $this->gallery_images : json_decode($this->gallery_images, true);
        return is_array($images) ? $images : [];
    }

    // Calculate progress percentage
    public function getProgressPercentageAttribute()
    {
        if ($this->target_amount == 0) {
            return 0;
        }
        
        return min(100, round(($this->raised_amount / $this->target_amount) * 100, 2));
    }

    // Get beneficiary count (computed if not stored)
    public function getBeneficiariesCountAttribute()
    {
        if (isset($this->attributes['beneficiaries_count']) && $this->attributes['beneficiaries_count'] !== null) {
            return $this->attributes['beneficiaries_count'];
        }
        
        if ($this->beneficiaries) {
            if (is_numeric($this->beneficiaries)) {
                return (int) $this->beneficiaries;
            }
            
            preg_match_all('/\d+/', $this->beneficiaries, $matches);
            if (!empty($matches[0])) {
                return (int) $matches[0][0];
            }
        }
        
        return $this->total_beneficiaries_target ?? 0;
    }

    // Check if fundraiser is active (based on dates)
    public function getIsActiveAttribute()
    {
        if ($this->status !== 'active') {
            return false;
        }
        
        $now = now();
        return $this->start_date <= $now && $this->end_date >= $now;
    }

    // Check if fundraiser is urgent
    public function getIsUrgentAttribute()
    {
        if (!$this->urgency_level) {
            return false;
        }
        
        return in_array($this->urgency_level, ['critical', 'urgent']);
    }

    // Get formatted raised amount
    public function getFormattedRaisedAmountAttribute()
    {
        return number_format($this->raised_amount, 0);
    }

    // Get formatted target amount
    public function getFormattedTargetAmountAttribute()
    {
        return number_format($this->target_amount, 0);
    }

    // Get updates count (from cause_updates table)
    public function getUpdatesCountAttribute()
    {
        if (isset($this->attributes['updates_count']) && $this->attributes['updates_count'] !== null) {
            return $this->attributes['updates_count'];
        }
        
        return $this->updates()->count();
    }

    // Get total donations amount
    public function getTotalDonationsAttribute()
    {
        return $this->donations()->where('payment_status', 'completed')->sum('amount');
    }

    // Check if fundraiser has ended
    public function getHasEndedAttribute()
    {
        if (!$this->end_date) {
            return false;
        }
        
        return now()->gt($this->end_date);
    }

    // Check if fundraiser is upcoming
    public function getIsUpcomingAttribute()
    {
        if (!$this->start_date) {
            return false;
        }
        
        return now()->lt($this->start_date);
    }

    // Get remaining amount needed
    public function getRemainingAmountAttribute()
    {
        return max(0, $this->target_amount - $this->raised_amount);
    }

    // Get formatted remaining amount
    public function getFormattedRemainingAmountAttribute()
    {
        return number_format($this->remaining_amount, 0);
    }

    // Get share URL
    public function getShareUrlAttribute()
    {
        return route('fundraisers.show', $this->slug);
    }

    // Get share text
    public function getShareTextAttribute()
    {
        return "Check out this fundraiser: " . $this->title;
    }

    // Get status badge class
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'secondary',
            'pending' => 'warning',
            'active' => 'success',
            'completed' => 'info',
            'cancelled' => 'danger',
            'rejected' => 'dark',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    // Get status text
    public function getStatusTextAttribute()
    {
        return ucfirst($this->status);
    }

    // Get urgency badge class
    public function getUrgencyBadgeAttribute()
    {
        $badges = [
            'low' => 'success',
            'normal' => 'info',
            'high' => 'warning',
            'urgent' => 'warning',
            'critical' => 'danger',
        ];

        return $badges[$this->urgency_level] ?? 'info';
    }

    // Get urgency text
    public function getUrgencyTextAttribute()
    {
        return ucfirst($this->urgency_level);
    }

    // Get recent donations
    public function recentDonations($limit = 5)
    {
        return $this->donations()
            ->where('payment_status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    // Get recent updates
    public function recentUpdates($limit = 3)
    {
        return $this->updates()
            ->where('is_public', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    // Get active milestones
    public function activeMilestones()
    {
        return $this->milestones()
            ->where('status', 'active')
            ->orderBy('sort_order', 'asc')
            ->get();
    }

    // Get FAQ list
    public function faqList()
    {
        return $this->faqs()
            ->orderBy('sort_order', 'asc')
            ->get();
    }
}