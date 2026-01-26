<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ServiceStory extends Model
{
    use HasFactory;

    protected $table = 'service_stories';

    protected $fillable = [
        'service_id',
        'title',
        'content',
        'image',
        'video_url',
        'type',
        'author_name',
        'author_position',
        'featured',
        'sort_order'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'sort_order' => 'integer'
    ];

    protected $appends = ['story_image_url'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    protected function storyImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image 
                ? asset('storage/' . $this->image) 
                : asset('images/default-story.jpg'),
        );
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
}