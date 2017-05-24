<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'title', 'body', 'active', 'published_at', 'deleted_at'];
    protected $dates = ['deleted_at', 'published_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPublishedDateAttribute()
    {
        return $this->published_at ? $this->published_at->toFormattedDateString() : false;
    }

    public function getIsActiveAttribute()
    {
        return $this->active;
    }
}
