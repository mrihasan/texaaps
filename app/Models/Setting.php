<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = [
        'org_name','org_slogan','address_line1','address_line2','contact_no1','contact_no2',
        'vat_reg_no','email','web',
    ];

}
