<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankLedger extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_id',
        'transaction_date',
        'transaction_code',
        'sl_no',
        'transaction_method_id',
        'amount',
        'particulars',
        'entry_by',
        'approve_status',
        'checked_by',
        'approved_by',
        'checked_date',
        'approved_date',
        'reftbl',
        'reftbl_id',
    ];

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }

    public function bank_account()
    {
        return $this->belongsTo('App\Models\BankAccount');
    }

    public function entryby()
    {
        return $this->belongsTo('App\Models\User', 'entry_by');
    }

    public function transaction_type()
    {
        return $this->belongsTo('App\Models\TransactionType');
    }

    public function transaction_method()
    {
        return $this->belongsTo('App\Models\TransactionMethod');
    }

}
