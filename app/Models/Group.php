<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Genres;
use App\Models\GroupUser;
use App\Models\Event;


class Group extends Model
{
    use HasFactory;
    use SoftDeletes;
    
  
    public $fillable = ['name','description','location','required_join','photo' ];
 
    /**
     * The attributes that should be mutated to dates.
     * scratchcode.io
     * @var array
     */
 
    protected $dates = [ 'deleted_at' ];

    public function created_user()
    {
        return $this->hasOne(User::class,'id','created_by');  
    }
    public function genres()
    {
        return $this->hasOne(Genres::class,'id','genres_id');
    }
    
    public function GroupUser()
    {
        return $this->hasMany(GroupUser::class,'group_id')->with('user');
    }

    public function PendingRequest()
    {
        return $this->hasMany(GroupUser::class,'group_id')->where('is_join',0);
    }

    public function Event()
    {
        return $this->hasMany(Event::class,'group_id')->with('event_created_by');
    }
    public function scopeSearch($query,$val)
    {
        return $query
        ->where('id',$val)
        ->Orwhere('name','like','%'.$val.'%');
    }
    public function getPhotoAttribute($value)
    {
        if ($value) {
            return asset('/groupimages/' . $value);
        } else {
            return null;
        }
    }
}
