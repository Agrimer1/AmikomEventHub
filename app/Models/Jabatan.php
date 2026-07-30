<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatan';

    protected $fillable = [
        'name',
        'created_by',
        'updated_by'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->user()->name;
            } else {
                $model->created_by = 'system';
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->user()->name;
            } else {
                $model->updated_by = 'system';
            }
        });
    }

    public function penguruses()
    {
        return $this->hasMany(Pengurus::class, 'jabatan_id');
    }
}
