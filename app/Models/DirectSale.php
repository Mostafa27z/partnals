<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DirectSale extends Model
{
    protected $fillable = [
        'line_id',
        'customer_id',
        'user_id',
        'buy_price',
        'sale_price',
        'profit',
        'sale_date',
        'notes'
    ];

    public function line()
    {
        return $this->belongsTo(Line::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
