<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'branch_id',
        'date',
        'contact_person',
        'contact_no',
        'amount',
        'amount_inword',
        'transaction_method_id',
        'prepared_by',
        'checked_by',
        'approved_by',
    ];

}
