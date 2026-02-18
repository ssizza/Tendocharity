<?php
// app/Models/Admin.php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use GlobalStatus, Notifiable;

    protected $table = 'admins';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'address' => 'object',
        'email_verified_at' => 'datetime',
        'status' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the role that owns the admin.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Get the notifications for the admin.
     */
    public function notifications()
    {
        return $this->hasMany(AdminNotification::class, 'user_id');
    }

    /**
     * Get unread notifications.
     */
    public function unreadNotifications()
    {
        return $this->notifications()->where('is_read', 0);
    }

    /**
     * Check if admin is super admin (role_id = 0)
     */
    public function isSuperAdmin()
    {
        return $this->role_id == 0;
    }

    /**
     * Check if admin has a specific permission.
     */
    public function hasPermission($permissionCode)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        if (!$this->role) {
            return false;
        }
        
        return $this->role->permissions()
            ->where('code', $permissionCode)
            ->exists();
    }

    /**
     * Get the admin's full name.
     */
    public function getFullNameAttribute()
    {
        return $this->name ?? $this->username;
    }

    /**
     * Get the admin's profile image URL.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }
        
        return asset('assets/admin/images/default.png');
    }

    /**
     * Scope a query to only include active admins.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope a query to only include banned admins.
     */
    public function scopeBanned($query)
    {
        return $query->where('status', 0);
    }
}