<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'mission',
        'vision',
        'description',
        'impact_summary',
        'featured_image',
        'gallery_images',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'sort_order',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'sort_order' => 'integer'
    ];

    protected $appends = ['image_url'];

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function stories(): HasMany
    {
        return $this->hasMany(ServiceStory::class);
    }

    public function caseStudies(): HasMany
    {
        return $this->hasMany(ServiceStory::class)->where('type', 'case_study');
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(ServiceStory::class)->where('type', 'testimonial');
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                // First check if the image exists in the new path (assets/images/service/)
                if ($this->featured_image && file_exists(public_path($this->featured_image))) {
                    return asset($this->featured_image);
                }
                
                // Then check if it exists in the old storage path
                if ($this->featured_image && file_exists(storage_path('app/public/' . $this->featured_image))) {
                    return asset('storage/' . $this->featured_image);
                }
                
                // If no image exists, return default
                return asset('images/default-service.jpg');
            }
        );
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('sort_order', '>', 0)->orderBy('sort_order');
    }
    public function storiesCount()
{
    return $this->hasMany(ServiceStory::class)->count();
}
}