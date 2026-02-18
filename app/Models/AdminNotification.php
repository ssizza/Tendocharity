<?php
// app/Models/AdminNotification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $table = 'admin_notifications';
    
    protected $guarded = ['id'];
    
    protected $casts = [
        'is_read' => 'boolean',
        'api_response' => 'boolean',
    ];

    /**
     * Get the admin that owns the notification
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    /**
     * Alias for admin() to maintain backward compatibility
     */
    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    /**
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', 0);
    }

    /**
     * Scope a query to only include read notifications.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', 1);
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update(['is_read' => 1]);
        }
    }

    /**
     * Get the time ago in human readable format.
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}