<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyName extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'code_name',
        'address',
        'address2',
        'contact_no',
        'contact_no2',
        'email',
        'web',
        'status',
    ];
//    public function brand()
//    {
//        return $this->hasMany('App\Models\Brand');
//    }
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

}
