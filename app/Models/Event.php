<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'category_id',
        'organizer_id',
        'title',
        'description',
        'date',
        'location',
        'price',
        'stock',
        'poster_path',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Scope query untuk memfilter event berdasarkan user/organizer.
     */
    public function scopeForUser(Builder $query, ?User $user = null): Builder
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return $query;
        }

        if ($user->isSuperAdmin()) {
            return $query;
        }

        if ($user->organizer) {
            return $query->where('organizer_id', $user->organizer->id);
        }

        return $query->whereRaw('1 = 0');
    }

    public function averageRating(): float
    {
        return (float) round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function reviewsCount(): int
    {
        return $this->reviews()->count();
    }

    public function getPosterUrlAttribute()
    {
        if (!$this->poster_path) {
            return asset('assets/concert.png');
        }

        if (Str::startsWith($this->poster_path, 'posters/')) {
            return asset('storage/' . $this->poster_path);
        }

        return asset('assets/' . $this->poster_path);
    }
}
