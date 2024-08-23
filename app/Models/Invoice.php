<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }
    public function entryBy()
    {
        return $this->belongsTo('App\Models\User','entry_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo('App\Models\User','updated_by');
    }
    public function walking_customers()
    {
        return $this->hasMany('App\Models\WalkingCustomer');
    }
    public function ledgers()
    {
        return $this->hasMany('App\Models\Ledger');
    }
//    public function invoice_detail()
//    {
//        return $this->belongsTo('App\Models\InvoiceDetail')->orderBy('created_at', 'desc');
//    }
    public function details()
    {
        return $this->hasMany(InvoiceDetail::class,'invoice_id');
    }

}
