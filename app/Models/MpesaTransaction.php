<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MpesaTransaction extends Model
{
     protected $fillable = [
        'transaction_id',
        'amount',
        'phone',
        'account',
        'first_name',
        'middle_name',
        'last_name',
        'status'
    ];
}
