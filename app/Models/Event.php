<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'category_id', 'title', 'description', 'date',
        'location', 'price', 'stock', 'poster_path'
        ];
        
        protected $casts = [
        'date' => 'datetime',
        ];
        // Menandakan atribut: 1 Event harus terpaut pada satu wujud Kategori
    public function category()
    {
    return $this->belongsTo(Category::class);
    }

    public function getPosterUrlAttribute()
    {
        if (!$this->poster_path) {
            return asset('assets/concert.png');
        }

        if (\Illuminate\Support\Str::startsWith($this->poster_path, 'posters/')) {
            return asset('storage/' . $this->poster_path);
        }

        return asset('assets/' . $this->poster_path);
    }
}
