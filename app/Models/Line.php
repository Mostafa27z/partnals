<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Line extends Model
{
    use HasFactory, SoftDeletes;
    
    protected static function booted()
    {
        static::saving(function ($line) {
            if ($line->provider && $line->last_invoice_date) {
                // Find the provider to get its invoice_day
                $provider = Provider::where('name', $line->provider)->first();
                if ($provider && $provider->invoice_day) {
                    try {
                        $date = Carbon::parse($line->last_invoice_date);
                        $newDay = $provider->invoice_day;
                        
                        // Ensure the day is valid for the given month (e.g., Feb 30 -> Feb 28/29)
                        $safeDay = min($newDay, $date->daysInMonth);
                        
                        $line->last_invoice_date = $date->day($safeDay)->format('Y-m-d');
                    } catch (\Exception $e) {
                        // Keep original if parsing fails
                    }
                }
            }
        });
    }

    protected $fillable = [
        'phone_number', 'provider', 'serial_number', 'plan_id', 'customer_id',
        'attached_at', 'distributor_id', 'status', 'sale_price', 'buy_price',
        'is_sold', 'system_type', 'second_phone', 'offer_name', 'branch_name', 
        'employee_name', 'gcode', 'line_type', 'package', 'payment_date', 
        'last_invoice_date', 'notes', 'added_by', 'for_sale'
    ];

    protected $casts = [
        'is_sold' => 'boolean',
        'attached_at' => 'datetime',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function distributor()
    {
        return $this->belongsTo(User::class, 'distributor_id');
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function providerData()
    {
        return $this->belongsTo(Provider::class, 'provider', 'name');
    }

    /**
     * Get number of days in a given month/year
     */
    private function getDaysInMonth($month, $year)
    {
        return Carbon::create($year, $month, 1)->daysInMonth;
    }

    /**
     * Get starting date of line attachment for a specific month
     */
    private function getAttachmentStartInMonth($month, $year)
    {
        if (!$this->attached_at) return null;

        $attachedAt = Carbon::parse($this->attached_at);
        $monthStart = Carbon::create($year, $month, 1)->startOfMonth();
        $monthEnd = Carbon::create($year, $month, 1)->endOfMonth();

        if ($attachedAt->gt($monthEnd)) return null;

        return $attachedAt->gt($monthStart) ? $attachedAt : $monthStart;
    }

    /**
     * Get daily cost in a given month
     */
    public function getDailyCost($month = null, $year = null)
    {
        if (!$this->plan) return 0;
        
        $month = $month ?: now()->month;
        $year = $year ?: now()->year;
        
        $providerPrice = $this->plan->provider_price;

        return $providerPrice / $this->getDaysInMonth($month, $year);
    }

    /**
     * حساب الربح التقديري (المتكرر فقط) لشهر معين
     * تمت إزالة الأرباح لمرة واحدة (البيع/إعادة البيع) لتتم معالجتها عبر التراكم التاريخي في التقارير
     */
    public function calculateProfit($month, $year)
    {
        // إذا تم بيع الخط كبيع نهائي، لا توجد أرباح متكررة له (الربح كان لمرة واحدة عند البيع)
        if ($this->is_sold) {
            return 0;
        }

        $daysInMonth = $this->getDaysInMonth($month, $year);
        
        // إذا لم يكن الخط مربوطاً أصلاً بعميل
        if (!$this->attached_at) return 0;

        $attachedAt = Carbon::parse($this->attached_at);
        $currentCalcDate = Carbon::create($year, $month, 1)->startOfMonth();
        $attachedMonth   = $attachedAt->copy()->startOfMonth();
        
        // لا يحسب ربح للخط في شهور تسبق تاريخ ربطه بالعميل
        if ($currentCalcDate->lt($attachedMonth)) {
            return 0;
        }
        
        // الربح الشهري المتكرر = الفرق بين ما يدفعه العميل والفاتورة
        $revenue = $this->plan ? $this->plan->price : 0;
        $cost = $this->plan ? $this->plan->provider_price : 0;

        return $revenue - $cost;
    }
}
