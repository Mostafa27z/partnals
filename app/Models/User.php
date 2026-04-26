<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'base_salary',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted()
    {
        static::deleted(function ($user) {
            if ($user->role && $user->role->name === 'موزع') {
                $user->lines()->delete();
            }
        });

        static::restored(function ($user) {
            if ($user->role && $user->role->name === 'موزع') {
                $user->lines()->restore();
            }
        });
    }

    /**
     * حساب إجمالي السلف المسحوبة في شهر معين
     */
    public function getMonthlyAdvances($month = null, $year = null)
    {
        $month = (int)($month ?: now()->month);
        $year = (int)($year ?: now()->year);
        return $this->advances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');
    }

    /**
     * التحقق مما إذا كانت السلفة المطلوبة تتجاوز الراتب
     */
    public function checkAdvanceStatus($amount)
    {
        $month = now()->month;
        $year = now()->year;
        
        $currentAdvances = $this->getMonthlyAdvances($month, $year);
        
        // حساب إجمالي المكافآت (تارجت + حرة) لهذا الشهر
        $targetBonusTotal = $this->bonuses()
                ->whereNotNull('target_id')
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->sum('amount');
            
        $extraBonusesTotal = $this->bonuses()
                ->whereNull('target_id')
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->sum('amount');
        
        $totalLimit = $this->base_salary + $targetBonusTotal + $extraBonusesTotal;
        $totalAfterAdvance = $currentAdvances + $amount;
        
        if ($totalAfterAdvance > $totalLimit) {
            return [
                'exceeds' => true,
                'message' => 'خطأ: إجمالي السلف ('.($totalAfterAdvance).') سيتجاوز الحد المسموح به (الراتب + المكافآت: '.$totalLimit.').',
                'current_total' => $totalAfterAdvance
            ];
        }
        
        return ['exceeds' => false];
    }

    /**
     * حساب عدد المبيعات (الطلبات المنفذة) في شهر معين
     */
    public function getMonthlySalesCount($month = null, $year = null)
    {
        $month = (int)($month ?: now()->month);
        $year = (int)($year ?: now()->year);
        
        $start = now()->setYear($year)->setMonth($month)->startOfMonth();
        $end = now()->setYear($year)->setMonth($month)->endOfMonth();

        return $this->getSalesCountForPeriod($start, $end);
    }

    /**
     * حساب عدد المبيعات (الطلبات المنفذة) في فترة زمنية محددة
     */
    public function getSalesCountForPeriod($start, $end)
    {
        return Request::where('requested_by', $this->id)
            ->where('status', 'done')
            ->whereBetween('updated_at', [$start, $end])
            ->count();
    }

    /**
     * حساب عدد الفواتير المدفوعة في فترة زمنية محددة
     */
    public function getInvoicesCountForPeriod($start, $end)
    {
        return Invoice::where('paid_by', $this->id)
            ->where('is_paid', true)
            ->whereBetween('payment_date', [$start, $end])
            ->count();
    }

    /**
     * الحصول على جميع المستهدفات مع حالة التقدم لكل منها
     */
    public function getTargetsWithProgress($month = null, $year = null)
    {
        $month = (int)($month ?: now()->month);
        $year = (int)($year ?: now()->year);

        $targets = Target::where('type', 'general')
            ->orWhereHas('users', function($q) {
                $q->where('users.id', $this->id);
            })->get();

        return $targets->map(function($target) use ($month, $year) {
            $start = $target->start_date ? \Carbon\Carbon::parse($target->start_date)->startOfDay() : now()->setYear($year)->setMonth($month)->startOfMonth();
            $end = $target->end_date ? \Carbon\Carbon::parse($target->end_date)->endOfDay() : now()->setYear($year)->setMonth($month)->endOfMonth();

            $count = 0;
            $scope = $target->scope ?: 'requests';

            if ($scope === 'requests' || $scope === 'both') {
                $count += $this->getSalesCountForPeriod($start, $end);
            }

            if ($scope === 'invoices' || $scope === 'both') {
                $count += $this->getInvoicesCountForPeriod($start, $end);
            }

            $isReleased = $this->bonuses()
                ->where('target_id', $target->id)
                ->when(!$target->start_date, function($q) use ($month, $year) {
                    $q->whereMonth('date', $month)->whereYear('date', $year);
                })
                ->exists();

            $isAchieved = $count >= $target->threshold;
            
            return (object) [
                'target' => $target,
                'current_progress' => $count,
                'threshold' => $target->threshold,
                'is_achieved' => $isAchieved,
                'is_released' => $isReleased,
                'can_release' => $isAchieved && !$isReleased
            ];
        });
    }

    /**
     * التحقق مما إذا كان قد تم صرف مكافأة هذا التارجت بالفعل
     */
    public function isTargetReleased($targetId, $month = null, $year = null)
    {
        $month = (int)($month ?: now()->month);
        $year = (int)($year ?: now()->year);

        return $this->bonuses()
            ->where('target_id', $targetId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->exists();
    }

    /**
     * التحقق من جميع المستهدفات وصرف المكافآت المحققة تلقائياً (لغير الأدمن)
     */
    public function checkAndReleaseTargets($month = null, $year = null)
    {
        // استبعاد الأدمن من المكافآت التلقائية
        if ($this->role && $this->role->name === 'admin') {
            return;
        }

        $month = (int)($month ?: now()->month);
        $year = (int)($year ?: now()->year);
        
        $targets = $this->getTargetsWithProgress($month, $year);

        foreach ($targets as $tp) {
            if ($tp->can_release) {
                Bonus::create([
                    'user_id' => $this->id,
                    'target_id' => $tp->target->id,
                    'amount' => $tp->target->reward,
                    'reason' => "مكافأة مستهدف (تلقائي): " . $tp->target->name,
                    'date' => now()->setYear($year)->setMonth($month)->endOfMonth(),
                ]);
            }
        }
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }

    public function advances()
    {
        return $this->hasMany(Advance::class);
    }

    public function targets()
    {
        return $this->belongsToMany(Target::class);
    }

    public function bonuses()
    {
        return $this->hasMany(Bonus::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function lines(): HasMany
    {
        return $this->hasMany(Line::class, 'distributor_id');
    }
}
