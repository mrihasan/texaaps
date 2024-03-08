<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'company_name_id',
        'product_type_id',
        'brand_id',
        'unit_id',
        'unitbuy_price',
        'unitsell_price',
        'low_stock',
        'description',
        'status',
    ];
    public function company_name()
    {
        return $this->belongsTo('App\Models\CompanyName');
    }
    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }
    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }
    public function product_type()
    {
        return $this->belongsTo('App\Models\ProductType');
    }
    public function inventory_details()
    {
        return $this->hasMany(InvoiceDetail::class,'product_id');
    }

}
