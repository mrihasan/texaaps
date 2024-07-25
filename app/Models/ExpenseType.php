<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseType extends Model
{
    use HasFactory;
    protected $fillable = [
        'expense_name',
        'type',
    ];
    public function expense()
    {
        return $this->hasMany('App\Models\Expense');
    }

}
