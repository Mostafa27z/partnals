<?php

// app/Models/Invoice.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsChanges;
class Invoice extends Model
{
    use HasFactory;
use LogsChanges;

    protected $fillable = [
        'line_id', 'customer_id', 'amount', 'operator_price', 'calculated_profit', 'is_paid', 'invoice_month', 'payment_date', 'paid_by', 'notes'
    ];
public function user()
{
    return $this->belongsTo(User::class, 'paid_by');
}
    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function payer() {
        return $this->belongsTo(User::class, 'paid_by');
    }
    public function line()
{
    return $this->belongsTo(Line::class);
}

    protected static function booted()
    {
        static::creating(function ($invoice) {
            if (!$invoice->customer_id && $invoice->line_id) {
                $line = $invoice->line;
                if ($line) {
                    $invoice->customer_id = $line->customer_id;
                }
            }
        });
    }


// public function user()
// {
//     return $this->belongsTo(User::class, 'paid_by');
// }

    
}

