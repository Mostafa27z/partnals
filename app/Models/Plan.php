<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use App\Traits\LogsChanges;
use Illuminate\Database\Eloquent\SoftDeletes;
class Plan extends Model
{
    use SoftDeletes, HasFactory;
    // use LogsChanges;
    protected $fillable = [
    'name', 'price', 'provider', 'provider_price', 'type', 'plan_code', 'penalty','deleted_at'
    ];

    public function scopeFilter($query, $request)
    {
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('provider', 'like', "%{$request->search}%")
                  ->orWhere('plan_code', 'like', "%{$request->search}%")
                  ->orWhere('type', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('provider')) {
            $query->where('provider', $request->provider);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        return $query;
    }

    public function lines()
    {
        return $this->hasMany(Line::class);
    }
}
