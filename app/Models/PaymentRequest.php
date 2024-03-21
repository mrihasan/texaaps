<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'req_no',
        'req_date',
        'customer_id',
        'product_id',
        'model',
        'workorder_refno',
        'workorder_date',
        'workorder_amount',
        'supplier_id',
        'contact_person',
        'contact_no',
        'amount',
        'account_name',
        'account_no',
        'bank_name',
        'transaction_method_id',
        'expected_bill',
        'expected_day',
        'checked_by',
        'approved_by'
        ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }
    public function bank_account()
    {
        return $this->belongsTo('App\Models\BankAccount');
    }
    public function transaction_method()
    {
        return $this->belongsTo('App\Models\TransactionMethod');
    }
    public function checkedBy()
    {
        return $this->belongsTo('App\Models\User','checked_by');
    }
    public function approvedBy()
    {
        return $this->belongsTo('App\Models\User','approved_by');
    }


}
