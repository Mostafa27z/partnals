<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::deleting(function ($target) {
            $target->users()->detach();
        });
    }

    protected $fillable = [
        'name',
        'type',
        'scope',
        'threshold',
        'reward',
        'start_date',
        'end_date',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function bonuses()
    {
        return $this->hasMany(Bonus::class);
    }
}
