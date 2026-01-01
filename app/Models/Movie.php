<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Optional: Casts ensure data types are correct when accessing properties
    protected $casts = [
        'release_year' => 'integer',
        'views' => 'integer',
    ];

    // Relationships
    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }

    public function isSeries()
    {
        return $this->type === 'series';
    }

    public function scopeTrending($query)
    {
        return $query->orderBy('views', 'desc')->take(10);
    }
}
