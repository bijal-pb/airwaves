<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PostMedia;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;


class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $appends = ['total_likes','total_unlikes','total_comments'];

    public function media()
    {
        return $this->hasMany(PostMedia::class,'post_id');
    }
    public function postBy()
    {
        return $this->hasOne(User::class,'id','user_id')->with('tracks');
    }
    public function getUser()
    {
        return $this->hasOne(User::class,'id','user_id')->withTrashed()->with('tracks');
    }
    public function postLikes()
    {
        return $this->hasMany(PostLike::class,'post_id');
    }
    public function postComments()
    {
        return $this->hasMany(PostComment::class,'post_id')->whereNull('comment_id')->with(['commentBy']);
    }
    public function getTotalLikesAttribute()
    {
        return $this->hasMany(PostLike::class,'post_id')->where('like',1)->count();
    }
    public function getTotalUnlikesAttribute()
    {
        return $this->hasMany(PostLike::class,'post_id')->where('like',2)->count();
    }
    public function getTotalCommentsAttribute()
    {
        return $this->hasMany(PostComment::class,'post_id')->count();
    }
    public function getCreatedAtAttribute($value)
    {
        return (new Carbon($value))->format('Y-m-d h:i:s');
    }
    public function getUpdatedAtAttribute($value)
    {
        return (new Carbon($value))->format('Y-m-d h:i:s');
    }
    public function scopeSearch($query,$val)
    { 
        if($val != NULL)
        {
        return $query
        ->where('id',$val)
        ->Orwhere('user_id','like','%'.$val.'%');
        }else{
            return $query;
        }
    }
}
