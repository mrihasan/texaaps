<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'code_no',
        'code_no',
        'contact_no1',
        'contact_no2',
        'status',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}
