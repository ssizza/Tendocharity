<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    
    protected $fillable = [
        'service_id',
        'name',
        'slug',
        'description',
        'image',
        'status',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'status' => 'string',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Scope for active categories
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Relationship with service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Relationship with fundraisers
    public function fundraisers()
    {
        return $this->hasMany(Fundraiser::class);
    }

    // Get active fundraisers for this category
    public function activeFundraisers()
    {
        return $this->fundraisers()->active();
    }
}