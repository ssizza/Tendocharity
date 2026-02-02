<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
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
    ];

    /**
     * Scope: only active categories
     * Matches Service::scopeActive()
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Category belongs to a service
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Category has many fundraisers
     */
    public function fundraisers()
    {
        return $this->hasMany(Fundraiser::class);
    }

    /**
     * Created by admin/user
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Updated by admin/user
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
