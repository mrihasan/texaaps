<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','contact_no1','contact_no2','gender','address','company_name_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function company_name()
    {
        return $this->belongsTo(CompanyName::class);
    }

}
