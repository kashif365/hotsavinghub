<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
        'method',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the model that was affected
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Scope for filtering by action
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for filtering by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for filtering by model
     */
    public function scopeByModel($query, $modelType, $modelId = null)
    {
        $query = $query->where('model_type', $modelType);
        
        if ($modelId) {
            $query->where('model_id', $modelId);
        }
        
        return $query;
    }

    /**
     * Scope for recent activities
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get formatted time ago
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get user name or 'System' if no user
     */
    public function getUserNameAttribute()
    {
        return $this->user ? $this->user->name : 'System';
    }

    /**
     * Get action icon
     */
    public function getActionIconAttribute()
    {
        $icons = [
            'create' => 'ri-add-line',
            'update' => 'ri-edit-line',
            'delete' => 'ri-delete-bin-line',
            'login' => 'ri-login-box-line',
            'logout' => 'ri-logout-box-line',
            'view' => 'ri-eye-line',
            'click' => 'ri-links-line',
            'export' => 'ri-download-line',
            'import' => 'ri-upload-line',
        ];

        return $icons[$this->action] ?? 'ri-information-line';
    }

    /**
     * Get action color
     */
    public function getActionColorAttribute()
    {
        $colors = [
            'create' => 'success',
            'update' => 'warning',
            'delete' => 'danger',
            'login' => 'info',
            'logout' => 'secondary',
            'view' => 'primary',
            'click' => 'info',
            'export' => 'info',
            'import' => 'success',
        ];

        return $colors[$this->action] ?? 'secondary';
    }
}