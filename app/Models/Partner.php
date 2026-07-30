<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Partner extends Model
{
    protected $fillable = ['name', 'logo_url'];

    public function getLogoFullUrlAttribute(): ?string
    {
        if (!$this->logo_url) {
            return null;
        }

        if (str_starts_with($this->logo_url, 'http://') || str_starts_with($this->logo_url, 'https://')) {
            return $this->logo_url;
        }

        if (Storage::disk('public')->exists($this->logo_url)) {
            return asset('storage/' . $this->logo_url);
        }

        return asset('storage/' . $this->logo_url);
    }
}
