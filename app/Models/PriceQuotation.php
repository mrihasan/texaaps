<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceQuotation extends Model
{
    use HasFactory;
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function entryBy()
    {
        return $this->belongsTo('App\Models\User','entry_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo('App\Models\User','updated_by');
    }

}
