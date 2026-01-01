<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatchProgress extends Model
{
    protected $table = 'watch_progress';
    protected $guarded = [];
    public $timestamps = false; // We manage last_watched_at manually

    protected $casts = [
        'is_finished' => 'boolean',
        'last_watched_at' => 'datetime',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function episode()
    {
        return $this->belongsTo(Episode::class);
    }
}
