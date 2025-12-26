<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\DateHelper;

class Post extends Model
{
    protected $fillable = [
        'title',
        'body',
        'user_id',
        'community_id',
        'media_path',
        'media_type',
    ];

    protected $appends = [
        'created_at_formatted',
        'updated_at_formatted',
        'created_at_relative',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function userVote()
    {
        return $this->votes()->where('user_id', auth()->id())->first();
    }

    /**
     * Accessor untuk format tanggal lengkap Indonesia
     */
    public function getCreatedAtFormattedAttribute()
    {
        return DateHelper::formatLengkap($this->created_at);
    }

    /**
     * Accessor untuk format tanggal update lengkap Indonesia
     */
    public function getUpdatedAtFormattedAttribute()
    {
        return DateHelper::formatLengkap($this->updated_at);
    }

    /**
     * Accessor untuk format waktu relatif (jam lalu, hari lalu, dll)
     */
    public function getCreatedAtRelativeAttribute()
    {
        return DateHelper::timeAgoIndonesian($this->created_at);
    }
}

