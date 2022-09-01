<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Group;

class Genres extends Model
{
    use HasFactory;
    use SoftDeletes;
    
  
    public $fillable = [ 'name' ];
 
    /**
     * The attributes that should be mutated to dates.
     * scratchcode.io
     * @var array
     */
 
    protected $dates = [ 'deleted_at' ];

    public function Group()
    {
        return $this->hasMany(Group::class,'groups_id');
    }
}
