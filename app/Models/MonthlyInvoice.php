<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'month',
        'year',
        'total_amount',
        'generated_at',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
     public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

