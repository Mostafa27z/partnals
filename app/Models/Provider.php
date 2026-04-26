<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = ['name', 'invoice_day'];

    protected static function booted()
    {
        static::updated(function ($provider) {
            // If the provider name changed, update the string reference in all related Lines
            if ($provider->wasChanged('name')) {
                $oldName = $provider->getOriginal('name');
                \App\Models\Line::where('provider', $oldName)->update(['provider' => $provider->name]);
            }

            // If the day changed (or name changed), re-save all related lines to trigger their date mutators
            if ($provider->wasChanged('invoice_day') || $provider->wasChanged('name')) {
                \App\Models\Line::where('provider', $provider->name)->chunk(100, function ($lines) {
                    foreach ($lines as $line) {
                        // Saving triggers the sync logic we just built in the Line model
                        $line->save();
                    }
                });
            }
        });
    }
}

