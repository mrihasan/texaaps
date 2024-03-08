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
        'approved_by',
        'approved_date',
        'usr_id',
        'transaction_code',
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

}
