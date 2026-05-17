<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = [
    'line_id',
    'customer_id',
    'request_type',
    'request_id',
    'status',
    'requested_by',
    'done_by',
    'notes',
];


    public function stopDetails()
    {
        return $this->hasOne(RequestStopLine::class);
    }
    public function requestedBy()
{
    return $this->belongsTo(User::class, 'requested_by');
}

public function doneBy()
{
    return $this->belongsTo(User::class, 'done_by');
}

    public function line()
    {
        return $this->belongsTo(Line::class)->withTrashed();
    }

public function resellDetails()
{
    return $this->hasOne(RequestResell::class);
}
public function changeChip()
{
    return $this->hasOne(RequestChangeChip::class);
}
public function pause()
{
    return $this->hasOne(RequestPauseLine::class);
}
public function resume()
{
    return $this->hasOne(RequestResumeLine::class);
}
public function changePlan()
{
    return $this->hasOne(RequestChangePlan::class);
}
public function changeDistributor()
{
    return $this->hasOne(RequestChangeDistributor::class);
}

public function changeDate()
{
    return $this->hasOne(RequestChangeDate::class);
}

/**
 * Check if there is an active request of a specific type on a line
 */
public static function hasActiveRequest($lineId, $type)
{
    return self::where('line_id', $lineId)
        ->where('request_type', $type)
        ->whereNotIn('status', ['done', 'cancelled'])
        ->exists();
}
}
