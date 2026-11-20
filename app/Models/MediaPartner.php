<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaPartner extends Model
{
    protected $fillable = [
        'name',
        'image_path',
        'created_by',
    ];

    public function getLogoAttribute(): ?string
    {
        return $this->image_path;
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
