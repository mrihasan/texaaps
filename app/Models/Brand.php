<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'company_name_id',
        'status',
    ];
    public function company_name()
    {
        return $this->belongsTo('App\Models\CompanyName');
    }
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

}
