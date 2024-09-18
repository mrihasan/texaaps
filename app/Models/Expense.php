<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


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

    public function calculateDepreciation($startDate, $endDate)
    {
        // Convert the dates to Carbon instances
        $expenseDate = Carbon::parse($this->expense_date);
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // If the expense date is after the range, it should not be included
        if ($expenseDate->gt($end)) {
            return 0;
        }

        // Calculate the full years of depreciation between expense date and end date
        $years = $expenseDate->diffInYears($end);
//dd($years);
        // Depreciation rate is provided as a percentage (e.g., 10 means 10%)
        $depreciationRate = $this->deprecation / 100;
//        dd($depreciationRate);
        // Calculate depreciated value
        $depreciatedValue = $this->expense_amount * pow(1 - $depreciationRate, $years);
//dd($depreciatedValue);
        return $depreciatedValue;
    }



}
