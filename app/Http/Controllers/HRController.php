<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Advance;
use App\Models\Bonus;
use App\Models\Salary;
use App\Models\Target;
use App\Models\Role;
use App\Notifications\TargetAssignedNotification;
use App\Notifications\TargetRemovedNotification;
use App\Notifications\TargetUpdatedNotification;
use Illuminate\Support\Facades\Notification;

class HRController extends Controller
{
    public function dashboard(Request $request)
    {
        $month = (int)$request->input('month', now()->month);
        $year = (int)$request->input('year', now()->year);

        // جلب الموظفين مع علاقاتهم المالية والشخصية
        $users = User::with(['advances', 'salaries', 'targets', 'bonuses', 'role' => function ($query) {
             // To ensure roles are fetched if any
        }])->get();

        // حساب حالة كل موظف للشهر المطلوب
        foreach ($users as $user) {
            /** @var \App\Models\User $user */
            $user->monthly_sales = $user->getMonthlySalesCount($month, $year);
            $user->targets_progress = $user->getTargetsWithProgress($month, $year);
            $user->total_advances = $user->getMonthlyAdvances($month, $year);
            
            // تحقق من الراتب الذي دُفع
            $salaryRecord = $user->salaries()->where('month', $month)->where('year', $year)->first();
            $user->is_paid = $salaryRecord ? ($salaryRecord->status === 'paid') : false;

            // إجمالي مكافآت التارجت التي تم "صرفها" بالفعل كـ Bonus
            $user->target_bonus_total = $user->bonuses()
                ->whereNotNull('target_id')
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->sum('amount');
            
            // إجمالي المكافآت الحرة (التي ليس لها target_id)
            $user->bonuses_total = $user->bonuses()
                ->whereNull('target_id')
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->sum('amount');
        }

        $allTargets = Target::with('users')->get();

        return view('admin.hr.dashboard', compact('users', 'allTargets', 'month', 'year'));
    }

    public function storeAdvance(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $user = User::findOrFail($request->user_id);
        
        // التحقق مما إذا كان يتجاوز الراتب + المكافآت
        $status = $user->checkAdvanceStatus($request->amount);

        if ($status['exceeds']) {
            return redirect()->back()->with('error', $status['message']);
        }

        Advance::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'date' => $request->date,
            'notes' => $request->notes,
            'status' => 'approved' // مباشر كأدمن
        ]);

        return redirect()->back()->with('success', 'تم تسجيل صرف السلفة بنجاح.');
    }

    public function storeBonus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'reason' => 'required|string|max:255'
        ]);

        Bonus::create($request->all());

        return redirect()->back()->with('success', 'تم تسجيل المكافأة بنجاح.');
    }

    public function paySalary(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0', // صافي الراتب المستحق
            'month' => 'required|integer',
            'year' => 'required|integer'
        ]);

        Salary::updateOrCreate(
            ['user_id' => $request->user_id, 'month' => $request->month, 'year' => $request->year],
            ['amount' => $request->amount, 'status' => 'paid']
        );

        return redirect()->back()->with('success', 'تم تسجيل دفع الراتب بنجاح.');
    }

    public function assignTarget(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'target_id' => 'required|exists:targets,id'
        ]);

        $users = User::whereIn('id', $request->user_ids)->get();
        foreach ($users as $user) {
            /** @var \App\Models\User $user */
            $user->targets()->syncWithoutDetaching([$request->target_id]);
            $user->notify(new TargetAssignedNotification(Target::find($request->target_id)));
        }

        return redirect()->back()->with('success', 'تم إسناد التارجت للموظفين المحددين بنجاح.');
    }

    public function storeTarget(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:general,specific',
            'threshold' => 'required|integer|min:1',
            'reward' => 'required|numeric|min:0',
            'scope' => 'required|in:requests,invoices,both',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        /** @var \App\Models\Target $target */
        $target = Target::create($request->all());

        // إذا كان عام، يتم إسناده لجميع الموظفين الحاليين (ما عدا الأدمن)
        if ($request->type === 'general') {
            $nonAdminRoles = Role::where('name', '!=', 'admin')->pluck('id');
            $users = User::whereIn('role_id', $nonAdminRoles)->get();
            $target->users()->sync($users->pluck('id'));

            // إرسال إشعار للجميع
            Notification::send($users, new TargetAssignedNotification($target));
        }

        return redirect()->back()->with('success', 'تم إضافة المستهدف بنجاح.');
    }

    public function updateTarget(Request $request, Target $target)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:general,specific',
            'threshold' => 'required|integer|min:1',
            'reward' => 'required|numeric|min:0',
            'scope' => 'required|in:requests,invoices,both',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        // التحقق من التغييرات في البيانات الأساسية للإشعارات
        $dataChanged = $target->name !== $request->name ||
                       (int)$target->threshold !== (int)$request->threshold ||
                       (float)$target->reward !== (float)$request->reward ||
                       $target->start_date != $request->start_date ||
                       $target->end_date != $request->end_date;

        // التحقق من التغييرات في الموظفين
        $oldUserIds = $target->users()->pluck('users.id')->toArray();
        $newUserIds = $request->user_ids ?? [];
        
        $addedUserIds = array_diff($newUserIds, $oldUserIds);
        $removedUserIds = array_diff($oldUserIds, $newUserIds);

        $target->update($request->all());

        if ($request->has('user_ids')) {
            $target->users()->sync($request->user_ids);
        }

        // إرسال الإشعارات
        if ($dataChanged) {
            $currentUsers = $target->users()->get();
            Notification::send($currentUsers, new TargetUpdatedNotification($target));
        }

        if (!empty($addedUserIds)) {
            $addedUsers = User::find($addedUserIds);
            Notification::send($addedUsers, new TargetAssignedNotification($target));
        }

        if (!empty($removedUserIds)) {
            $removedUsers = User::find($removedUserIds);
            Notification::send($removedUsers, new TargetRemovedNotification($target));
        }

        // إذا تم تحديث النوع إلى عام، يتم إسناده لجميع الموظفين الحاليين (ما عدا الأدمن)
        if ($target->type === 'general') {
            $nonAdminRoles = Role::where('name', '!=', 'admin')->pluck('id');
            $allUsers = User::whereIn('role_id', $nonAdminRoles)->get();
            $target->users()->sync($allUsers->pluck('id'));
            
            // في حالة العام، قد نرسل إشعاراً للجميع أيضاً إذا لزم الأمر
        }

        return redirect()->back()->with('success', 'تم تحديث المستهدف بنجاح.');
    }

    public function destroyTarget(Target $target)
    {
        $target->delete();
        return redirect()->back()->with('success', 'تم حذف المستهدف بنجاح.');
    }

    public function releaseTargetBonus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'target_id' => 'required|exists:targets,id',
            'month' => 'required|integer',
            'year' => 'required|integer',
        ]);

        $user = User::findOrFail($request->user_id);
        $target = Target::findOrFail($request->target_id);

        // التأكد من عدم الصرف مسبقاً
        if ($user->isTargetReleased($target->id, $request->month, $request->year)) {
            return redirect()->back()->with('error', 'تم صرف مكافأة هذا التارجت بالفعل مسبقاً.');
        }

        // إنشاء المكافأة
        Bonus::create([
            'user_id' => $user->id,
            'target_id' => $target->id,
            'amount' => $target->reward,
            'reason' => "مكافأة تارجت: " . $target->name,
            'date' => now()->setYear((int)$request->year)->setMonth((int)$request->month)->endOfMonth(),
        ]);

        return redirect()->back()->with('success', 'تم صرف مكافأة التارجت للموظف بنجاح.');
    }

    // واجهة للتأكد من حالة السلفة قبل الإرسال للمودال
    public function checkAdvanceAjax(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $status = $user->checkAdvanceStatus($request->amount);

        return response()->json($status);
    }
}
