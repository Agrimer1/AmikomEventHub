<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'discount_amount',
        'min_transaction',
        'max_discount',
        'usage_limit',
        'used_count',
        'is_active',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'discount_amount' => 'decimal:2',
            'min_transaction' => 'decimal:2',
            'max_discount' => 'decimal:2',
            'usage_limit' => 'integer',
            'used_count' => 'integer',
            'is_active' => 'boolean',
            'expired_at' => 'datetime',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Cek apakah kode promo valid untuk total transaksi tertentu.
     */
    public function isValidForAmount(float $subtotal): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expired_at && Carbon::now()->greaterThan($this->expired_at)) {
            return false;
        }

        if ($this->usage_limit > 0 && $this->used_count >= $this->usage_limit) {
            return false;
        }

        if ($subtotal < $this->min_transaction) {
            return false;
        }

        return true;
    }

    /**
     * Hitung nilai besaran diskon berdasarkan nominal transaksi.
     */
    public function calculateDiscount(float $subtotal): float
    {
        if (!$this->isValidForAmount($subtotal)) {
            return 0.0;
        }

        if ($this->type === 'percentage') {
            $discount = ($subtotal * $this->discount_amount) / 100;
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = (float) $this->max_discount;
            }
            return $discount;
        }

        // Tipe fixed
        return min((float) $this->discount_amount, $subtotal);
    }
}
