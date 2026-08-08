<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'type',
    ];

    public function circles(): HasMany
    {
        return $this->hasMany(Circle::class);
    }
}
