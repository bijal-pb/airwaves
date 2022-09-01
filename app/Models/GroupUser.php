<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Group;
use App\Models\User;

class GroupUser extends Model
{
    use HasFactory;

    public $fillable = [ 'is_admin' ];

    public function Group()
    {
        return $this->hasMany(Group::class,'group_id');
    }
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
