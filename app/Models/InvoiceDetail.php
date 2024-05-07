<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
