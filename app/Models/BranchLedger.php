<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchLedger extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_id',
        'transaction_date',
        'transaction_code',
        'transaction_method_id',
        'amount',
        'comments',
        'entry_by',
        'approve_status'
    ];
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }
    public function entryby()
    {
        return $this->belongsTo('App\Models\User','entry_by');
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
