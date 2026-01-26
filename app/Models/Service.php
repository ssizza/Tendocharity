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
            get: fn () => $this->featured_image 
                ? asset('storage/' . $this->featured_image) 
                : asset('images/default-service.jpg'),
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
}