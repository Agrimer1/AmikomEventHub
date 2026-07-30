<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    protected $fillable = [
        'event_id',
        'promo_code_id',
        'order_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_price',
        'discount_amount',
        'status',
        'snap_token',
        'payment_url',
        'reminder_sent_at',
    ];

    protected $casts = [
        'reminder_sent_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function promoCode(): BelongsTo
    {
        return $this->belongsTo(PromoCode::class, 'promo_code_id');
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Scope query untuk memfilter transaksi berdasarkan organizer milik user.
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
            $organizerId = $user->organizer->id;
            return $query->whereHas('event', function (Builder $q) use ($organizerId) {
                $q->where('organizer_id', $organizerId);
            });
        }

        return $query->whereRaw('1 = 0');
    }
}