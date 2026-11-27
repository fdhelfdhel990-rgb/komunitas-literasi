<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'location',
        'registration_status',
        'schedule',
        'registration_deadline',
        'volunteer_quota',
        'form_url',
        'poster_path',
        'document_path',
        'description',
        'created_by',
    ];

    protected $casts = [
        'schedule'              => 'date',
        'registration_deadline' => 'date',
        'volunteer_quota'       => 'integer',
    ];

    public function getTitleAttribute(): string
    {
        return $this->name;
    }

    public function getAreaAttribute(): string
    {
        return $this->location;
    }

    public function getDeadlineAttribute()
    {
        return $this->registration_deadline;
    }

    public function getQuotaAttribute(): ?int
    {
        return $this->volunteer_quota;
    }

    public function getStatusAttribute(): string
    {
        return $this->registration_status;
    }

    public function getFormLinkAttribute(): ?string
    {
        return $this->form_url;
    }

    public function getPosterImageAttribute(): ?string
    {
        return $this->poster_path;
    }

    public function getJuknisFileAttribute(): ?string
    {
        return $this->document_path;
    }

    public function getCategoryAttribute(): string
    {
        return 'Volunteer Event';
    }

    public function getBadgeTopAttribute(): string
    {
        $words = explode(' ', trim($this->name));
        return strtoupper(implode("\n", array_slice($words, 0, 2)));
    }

    public function getBadgeBottomAttribute(): string
    {
        return 'RUANG BACA BERGERAK';
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}