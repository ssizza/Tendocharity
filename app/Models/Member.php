<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'members';
    
    protected $fillable = [
        'name',
        'email',
        'category_id',
        'status',
        'image',
        'position',
        'bio',
        'social_media',
    ];

    protected $casts = [
        'social_media' => 'array',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(TeamCategory::class, 'category_id');
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('assets/images/default-team.jpg');
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        // Handle the path from your existing data
        if (str_starts_with($this->image, '/uploads/')) {
            return asset($this->image);
        }

        return asset($this->image);
    }

    public function getSocialMediaLinksAttribute()
    {
        $socialMedia = $this->social_media ?? [];
        $links = [];

        $platforms = [
            'facebook' => 'Facebook',
            'twitter' => 'Twitter',
            'instagram' => 'Instagram',
            'linkedin' => 'LinkedIn',
            'youtube' => 'YouTube',
            'tiktok' => 'TikTok',
            'github' => 'GitHub',
            'website' => 'Website'
        ];

        foreach ($platforms as $key => $label) {
            if (isset($socialMedia[$key]) && !empty($socialMedia[$key])) {
                $links[$key] = [
                    'url' => $socialMedia[$key],
                    'label' => $label
                ];
            }
        }

        return $links;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->status == 'active') {
            return '<span class="badge badge--success">Active</span>';
        }
        return '<span class="badge badge--danger">Inactive</span>';
    }

    public function getShortBioAttribute($length = 100)
    {
        return strip_tags(Str::limit($this->bio, $length));
    }
}