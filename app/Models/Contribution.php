<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    protected $fillable = [
        'service_id', 'name', 'slug', 'description', 'image',
        'status', 'sort_order', 'meta_title', 'meta_description',
        'meta_keywords', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'status' => 'string',
    ];

    
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', 'inactive');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function fundraisers()
    {
        return $this->hasMany(Fundraiser::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}