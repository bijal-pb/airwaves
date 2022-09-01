<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;
    
  
    public $fillable = [ 'name' ,'description','location','event_time','event_date','event_photo'];
 
    /**
     * The attributes that should be mutated to dates.
     * scratchcode.io
     * @var array
     */
 
    protected $dates = [ 'deleted_at' ];

    public function group()
    {
        return $this->hasOne(Group::class,'id','group_id');
    }
    public function scopeSearch($query,$val)
    {
        return $query
        ->where('id',$val)
        ->Orwhere('name','like','%'.$val.'%');
    }

    public function event_created_by()
    {
        return $this->hasOne(User::class,'id','event_by');
    }
    public function getEventPhotoAttribute($value)
    {
        if ($value) {
            return asset('/eventimages/' . $value);
        } else {
            return null;
        }
    }

}
