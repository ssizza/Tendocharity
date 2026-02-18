<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
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
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'social_media' => 'array',
        'status' => 'string'
    ];

    public function category()
    {
        return $this->belongsTo(TeamCategory::class, 'category_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('assets/images/team/' . $this->image);
        }
        return asset('assets/images/default-profile.png');
    }

    public function getSocialLinksAttribute()
    {
        return is_array($this->social_media) ? $this->social_media : [];
    }

    public function getFacebookAttribute()
    {
        return $this->social_links['facebook'] ?? null;
    }

    public function getTwitterAttribute()
    {
        return $this->social_links['twitter'] ?? null;
    }

    public function getLinkedinAttribute()
    {
        return $this->social_links['linkedin'] ?? null;
    }

    public function getInstagramAttribute()
    {
        return $this->social_links['instagram'] ?? null;
    }

    public function getWebsiteAttribute()
    {
        return $this->social_links['website'] ?? null;
    }

    public function getStatusBadgeAttribute()
    {
        $html = '';
        if ($this->status == 'active') {
            $html = '<span class="badge badge--success">' . trans('Active') . '</span>';
        } else {
            $html = '<span class="badge badge--warning">' . trans('Inactive') . '</span>';
        }
        return $html;
    }
}