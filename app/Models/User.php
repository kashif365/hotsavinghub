<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'phone',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Scope for active users
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for admin users
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    // Check if user is admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Check if user is regular user
    public function isUser()
    {
        return $this->role === 'user';
    }

    // Check if user is active
    public function isActive()
    {
        return $this->status === 'active';
    }

    // Check if user has access to specific module
    public function hasAccess($module)
    {
        // Admin has access to everything
        if ($this->isAdmin()) {
            return true;
        }

        // User role restrictions
        if ($this->isUser()) {
            $restrictedModules = [
                'contacts',
                'newsletters', 
                'users',
                'customers',
                'settings'
            ];
            
            return !in_array($module, $restrictedModules);
        }

        return false;
    }

    // Get user's full name
    public function getFullNameAttribute()
    {
        return $this->name;
    }
}
