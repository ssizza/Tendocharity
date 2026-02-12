<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamCategory extends Model
{
    protected $table = 'team_categories';
    
    protected $fillable = [
        'id',
        'name',
        'slug',
        'description',
        'status',
        'sort_order',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Scope for active categories
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // Relationship with members
    public function members()
    {
        return $this->hasMany(Member::class, 'category_id');
    }

    // Get active members count
    public function activeMembersCount()
    {
        return $this->members()->active()->count();
    }
}