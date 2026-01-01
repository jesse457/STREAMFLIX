<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['user_id', 'name', 'avatar', 'is_kid'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Interaction: My List
    public function myList()
    {
        return $this->belongsToMany(Movie::class, 'my_list')
                    ->withPivot('added_at')
                    ->orderByPivot('added_at', 'desc');
    }

    // Interaction: Continue Watching
    public function watchProgress()
    {
        return $this->hasMany(WatchProgress::class);
    }
}
