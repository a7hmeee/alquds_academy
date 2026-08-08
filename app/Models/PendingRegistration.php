<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingRegistration extends Model
{
    protected $fillable = [
        'email',
        'name',
        'password',
        'phone',
        'age',
        'country',
        'verification_code',
        'attempts',
        'last_sent_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'age' => 'integer',
            'attempts' => 'integer',
            'last_sent_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeValid($query)
    {
        return $query->where('expires_at', '>=', now())->where('attempts', '<', 3);
    }

    public function hasReachedMaxAttempts(): bool
    {
        return $this->attempts >= 3;
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
