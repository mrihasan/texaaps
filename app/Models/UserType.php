<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasFactory;

    protected $fillable = ['id','title','status'];

    public function user()
    {
        return $this->hasOne('App\Models\User');
    }


    public function user_type_role(){
        return $this->belongsToMany(Role::class, 'role_user_type');
    }
}
