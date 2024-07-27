<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = [
        'expense_type_id',
        'expense_date',
        'expense_amount',
        'status',
        'comments',
        'branch_id',
        'checked_by',
        'approved_by',
        'checked_date',
        'approved_date',
        'usr_id',
        'transaction_code',
        'sl_no',
        'type',
        'deprecation',
    ];

    public function expense_type()
    {
        return $this->belongsTo('App\Models\ExpenseType');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }
    public function approvedBy()
    {
        return $this->belongsTo('App\Models\User','approved_by');
    }
    public function transaction_method()
    {
        return $this->belongsTo('App\Models\TransactionMethod');
    }
    public function checkedBy()
    {
        return $this->belongsTo('App\Models\User','checked_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo('App\Models\User','updated_by');
    }

}
