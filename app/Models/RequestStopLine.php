<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestStopLine extends Model
{
    protected $fillable = [
        'request_id',
        'last_invoice_date',
        'reason',
        'comment',
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}
