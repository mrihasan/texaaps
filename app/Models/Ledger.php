<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_date',
        'transaction_code',
        'transaction_type',
        'amount',
        'transaction_method',
        'entry_by',
        'comments',
    ];
    public function walking_customers()
    {
        return $this->hasMany(WalkingCustomer::class);
    }
    public function transaction_type()
    {
        return $this->belongsTo('App\Models\TransactionType');
    }
    public function transaction_method()
    {
        return $this->belongsTo('App\Models\TransactionMethod');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function entryby()
    {
        return $this->belongsTo('App\Models\User','entry_by');
    }
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }
    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice');
    }

}
