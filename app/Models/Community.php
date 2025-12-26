<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'owner_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}

