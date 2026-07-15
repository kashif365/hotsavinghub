<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'first_name',
        'last_name', 
        'email',
        'phone',
        'subject',
        'message',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Accessor for full name
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Scope for new contacts
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    // Scope for read contacts
    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }
}
