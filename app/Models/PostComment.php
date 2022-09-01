<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


class PostComment extends Model
{
    use HasFactory;

    public function replies()
    {
        return $this->hasMany(PostComment::class, 'comment_id');
    }
    public function commentBy()
    {
        return $this->hasOne(User::class,'id','user_id')->with('tracks');
    }
    public function getCreatedAtAttribute($value)
    {
        return (new Carbon($value))->format('Y-m-d h:i:s');
    }
    public function getUpdatedAtAttribute($value)
    {
        return (new Carbon($value))->format('Y-m-d h:i:s');
    }
}
