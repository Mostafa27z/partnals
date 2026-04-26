<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestChangeDistributor extends Model
{
    protected $fillable = [
        'request_id', 'old_distributor_id', 'new_distributor_id', 'reason'
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function oldDistributor()
    {
        return $this->belongsTo(User::class, 'old_distributor_id');
    }

    public function newDistributor()
    {
        return $this->belongsTo(User::class, 'new_distributor_id');
    }
}
