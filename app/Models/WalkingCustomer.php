<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalkingCustomer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'mobile',
        'address',
    ];
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }

}
