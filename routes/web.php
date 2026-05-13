<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LineController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\HRController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ChangeLogController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\RoleController;

Route::get('/test-session', function () {
    session(['test_key' => 'تم الحفظ']);
    return 'Session set';
});
Route::get('/check-session', function () {
    return session('test_key', 'لا توجد جلسة');
});

Route::get('lang/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'ar'])) {
        abort(400);
    }

    session(['locale' => $locale]);
    return back();
})->name('lang.switch');

Route::middleware(['auth', 'condition.is.active:manage permissions'])->group(function () {
    Route::get('/admin/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::post('/admin/permissions/update', [PermissionController::class, 'update'])->name('permissions.update');
});

Route::get('/for-sale', function (\Illuminate\Http\Request $request) {
    $query = \App\Models\Line::withoutGlobalScope('distributor')
        ->with('plan')
        ->where('for_sale', true)
        ->where('is_sold', false)
        ->whereNotNull('sale_price');

    if ($request->filled('provider')) {
        $query->where('provider', $request->provider);
    }

    if ($request->filled('plan_id')) {
        $query->where('plan_id', $request->plan_id);
    }

    $lines = $query->orderBy('sale_price')->get();
    
    $providers = \App\Models\Line::withoutGlobalScope('distributor')
        ->where('for_sale', true)
        ->where('is_sold', false)
        ->distinct()
        ->pluck('provider');

    $plans = \App\Models\Plan::whereHas('lines', function($q) {
        $q->withoutGlobalScope('distributor')
          ->where('for_sale', true)
          ->where('is_sold', false);
    })->get();

    return view('public.for-sale', compact('lines', 'providers', 'plans'));
})->name('public.for-sale');

Route::get('home', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    $isDistributor = $user->role && $user->role->name === 'موزع';

    $activeCustomersCount = \App\Models\Customer::when($isDistributor, function($q) use ($user) {
        $q->whereHas('lines', function($lineQ) use ($user) {
            $lineQ->where('distributor_id', $user->id);
        });
    })->count();

    $pendingRequestsCount = \App\Models\Request::where('status', 'pending')
        ->when($isDistributor, function($q) use ($user) {
            $q->whereHas('line', function($lineQ) use ($user) {
                $lineQ->where('distributor_id', $user->id);
            });
        })->count();

    $newLinesCount = \App\Models\Line::whereMonth('created_at', now()->month)
        ->when($isDistributor, function($q) use ($user) {
            $q->where('distributor_id', $user->id);
        })->count();

    return view('dashboard', compact('activeCustomersCount', 'pendingRequestsCount', 'newLinesCount'));
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/home', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/ajax/plans/by-provider', function (\Illuminate\Http\Request $request) {
    $provider = $request->q;
    return \App\Models\Plan::where('provider', $provider)->select('id', 'name')->get();
});

Route::middleware(['auth', 'condition.is.active:manage dashboard'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // ✅ Route to permissions view
    // Route::get('/admin/permissions', [PermissionController::class, 'index'])->name('permissions.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth', 'verified', 'condition.is.active:edit company details'])->group(function () {
    Route::get('/admin/company', [CompanyController::class, 'edit'])->name('company.edit');
    Route::post('/admin/company', [CompanyController::class, 'store'])->name('company.store');
    Route::put('/admin/company/update/{id}', [CompanyController::class, 'update'])->name('company.update');

});
Route::middleware(['auth', 'condition.is.active:manage customers'])->prefix('admin')->group(function () {
    Route::get('customers/trashed', [CustomerController::class, 'trashed'])->name('customers.trashed');
    Route::post('customers/{id}/restore', [CustomerController::class, 'restore'])->name('customers.restore');
    Route::delete('customers/{id}/force-delete', [CustomerController::class, 'forceDelete'])->name('customers.forceDelete');
    Route::get('export-customers', [CustomerController::class, 'export'])->name('customers.export');
    Route::get('ajax/customers/search', [CustomerController::class, 'searchByNationalId'])->name('ajax.customers.search');
    Route::resource('customers', CustomerController::class);
});
// use App\Http\Controllers\PlanController;

Route::prefix('admin')->middleware(['auth', 'condition.is.active:manage plans'])->group(function () {
    // resource + export
    
    Route::get('plans-export', [PlanController::class, 'export'])->name('plans.export');

    // ✅ لا تكرر prefix('plans') لأنه بالفعل داخل plans resource
    Route::get('/plans/trashed', [PlanController::class, 'trashed'])->name('plans.trashed');
    Route::post('/plans/{id}/restore', [PlanController::class, 'restore'])->name('plans.restore');
    Route::delete('/plans/{id}/force-delete', [PlanController::class, 'forceDelete'])->name('plans.force-delete');
    Route::resource('plans', PlanController::class);
});


Route::prefix('admin/lines')->middleware(['auth', 'condition.is.active:manage lines'])->group(function () {
    Route::get('import', [LineController::class, 'importForm'])->name('lines.import.form');
    Route::post('import', [LineController::class, 'importProcess'])->name('lines.import.process');
    Route::post('/export-selected', [LineController::class, 'exportSelected'])->name('lines.export.selected');
    Route::post('/bulk-delete', [LineController::class, 'bulkDelete'])->name('lines.bulk-delete');
    Route::post('/bulk-update-distributor', [LineController::class, 'bulkUpdateDistributor'])->name('lines.bulk-update-distributor');
});

Route::prefix('admin')->middleware(['auth', 'condition.is.active:manage lines'])->group(function () {
    Route::resource('providers', ProviderController::class);
});
Route::middleware(['auth', 'condition.is.active:manage invoices'])->group(function () {
// routes/web.php


    Route::get('lines/{line}/pay', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('lines/{line}/pay', [InvoiceController::class, 'store'])->name('invoices.store');
Route::get('/lines/{line}', [LineController::class, 'show'])->name('lines.show');

    Route::get('/customers/{customer}/invoices', [InvoiceController::class, 'customerInvoices'])->name('customers.invoices');
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::post('/invoices/import', [InvoiceController::class, 'import'])->name('invoices.import');
    Route::post('/invoices/import-operator', [InvoiceController::class, 'importOperatorPrice'])->name('invoices.import-operator');
    Route::post('/invoices/import-customer', [InvoiceController::class, 'importCustomerPrice'])->name('invoices.import-customer');
    Route::get('/invoices/sample', [InvoiceController::class, 'downloadSample'])->name('invoices.sample');

});


Route::middleware(['auth', 'condition.is.active:manage lines'])->prefix('admin')->group(function () {

    /** ✅ الخطوط المرتبطة بعميل محدد */
    Route::prefix('customers/{customer}/lines')->name('customers.lines.')->group(function () {
        Route::get('/', [LineController::class, 'index'])->name('index');
        Route::get('create', [LineController::class, 'create'])->name('create');
        Route::post('/', [LineController::class, 'store'])->name('store');
        Route::get('{line}/edit', [LineController::class, 'edit'])->name('edit');
        Route::put('{line}', [LineController::class, 'update'])->name('update');
        Route::delete('{line}', [LineController::class, 'destroy'])->name('destroy');
    });

    /** ✅ الخطوط العامة (بدون ربط بعميل) */
   Route::prefix('lines')->name('lines.')->group(function () {
    // ثابتة
    Route::get('all', [LineController::class, 'all'])->name('all');
    Route::get('bulk-distributors', [LineController::class, 'bulkDistributorsIndex'])->name('bulk-distributors');
    Route::get('delete-lines', [LineController::class, 'deleteIndex'])->name('delete-index');
    Route::get('create', [LineController::class, 'createStandalone'])->name('create');
    Route::post('/', [LineController::class, 'storeStandalone'])->name('store');
    Route::get('export', [LineController::class, 'export'])->name('export');

    // ديناميكية
    Route::get('{line}/edit', [LineController::class, 'editStandalone'])->name('edit');
    Route::put('{line}', [LineController::class, 'updateStandalone'])->name('update'); // ✅ هنا
    Route::delete('{line}', [LineController::class, 'destroyStandalone'])->name('destroy');
    Route::get('{line}/invoices', [InvoiceController::class, 'lineInvoices'])->name('invoices');
    // سلة المحذوفات
    Route::get('/trashed', [LineController::class, 'trashed'])->name('trashed');

    // استرجاع خط
    Route::post('/{id}/restore', [LineController::class, 'restore'])->name('restore');

    // حذف نهائي
    Route::delete('/{id}/force-delete', [LineController::class, 'forceDelete'])->name('forceDelete');
    });
});
// search




Route::middleware(['auth', 'condition.is.active:manage users'])->prefix('admin')->group(function () {
    Route::get('users/trashed', [UserController::class, 'trashed'])->name('users.trashed');
    Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete');
    Route::resource('users', UserController::class);
});

Route::middleware(['auth', 'condition.is.active:manage roles'])->prefix('admin')->group(function () {
    Route::resource('roles', RoleController::class);
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::prefix('requests')->name('requests.')->middleware('condition.is.active:manage requests')->group(function () {
        // --- STATIC ROUTES (Must be before dynamic routes) ---
        Route::get('/all', [RequestController::class, 'all'])->name('all');
        Route::get('/summary', [RequestController::class, 'summary'])->name('summary');
        Route::get('/history', [RequestController::class, 'history'])->name('history');
        Route::get('/stop-lines', [RequestController::class, 'stopLineRequests'])->name('stop-lines');
        
        // Bulk Actions
        Route::put('/bulk-update', [RequestController::class, 'bulkUpdate'])->name('bulk-update');
        Route::post('/bulk-action', [RequestController::class, 'bulkAction'])->name('bulk-action');

        // Imports
        Route::post('/stop/import', [RequestController::class, 'importStopRequests'])->name('stop.import');
        Route::post('/resell/import', [RequestController::class, 'importResellRequests'])->name('resell.import');
        Route::post('/change-plan/import', [RequestController::class, 'importChangePlanRequests'])->name('change-plan.import');
        Route::post('/change-chip/import', [RequestController::class, 'importChangeChipRequests'])->name('change-chip.import');
        Route::post('/change-distributor/import', [RequestController::class, 'importChangeDistributorRequests'])->name('change-distributor.import');
        Route::post('/change-date/import', [RequestController::class, 'importChangeDateRequests'])->name('change-date.import');
        Route::post('/resume/import', [RequestController::class, 'importResumeRequests'])->name('resume.import');
        Route::post('/pause/import', [RequestController::class, 'importPauseRequests'])->name('pause.import');

        // Stores
        Route::post('/resell/store', [RequestController::class, 'storeResell'])->name('resell.store');
        Route::post('/change-plan', [RequestController::class, 'storeChangePlan'])->name('change-plan.store');
        Route::post('/change-chip/store', [RequestController::class, 'storeChangeChip'])->name('change-chip.store');
        Route::post('/pause/store', [RequestController::class, 'storePause'])->name('pause.store');
        Route::post('/resume/store', [RequestController::class, 'storeResume'])->name('resume.store');
        Route::post('/change-date/store', [RequestController::class, 'storeChangeDate'])->name('change-date.store');
        Route::post('/change-distributor/store', [RequestController::class, 'storeChangeDistributor'])->name('change-distributor.store');
        Route::post('/stop/store', [RequestController::class, 'storeStop'])->name('stop.store');

        // Resell specifics
        Route::get('/resell/choose-line', [RequestController::class, 'chooseLineForResell'])->name('resell.choose-line');
        Route::get('/resell', [RequestController::class, 'resellRequests'])->name('resell.index');

        // --- DYNAMIC ROUTES WITH {line} ---
        Route::get('/resell/{line}/create', [RequestController::class, 'createResell'])->name('resell.create');
        Route::get('/resell/{line}', [RequestController::class, 'createResell'])->name('resell.create.alt');
        Route::get('/change-plan/{line}', [RequestController::class, 'createChangePlan'])->name('change-plan.create');
        Route::get('/change-chip/{line}', [RequestController::class, 'createChangeChip'])->name('change-chip.create');
        Route::get('/pause/{line}', [RequestController::class, 'createPause'])->name('pause.create');
        Route::get('/resume/{line}/create', [RequestController::class, 'createResume'])->name('resume.create');
        Route::get('/change-date/{line}', [RequestController::class, 'createChangeDate'])->name('change-date.create');
        Route::get('/change-distributor/{line}', [RequestController::class, 'createChangeDistributor'])->name('change-distributor.create');
        Route::get('/stop/{line}', [RequestController::class, 'createStop'])->name('stop.create');

        // --- DYNAMIC ROUTES WITH {request} (Must be at the bottom) ---
        Route::get('/resell/{request}/details', [RequestController::class, 'resellDetails'])->name('resell.details');
        Route::post('/{request}/complete-sale', [RequestController::class, 'completeResellSale'])->name('complete-sale');
        Route::put('/{request}/update-details', [RequestController::class, 'updateDetails'])->name('update-details');
        Route::put('/{request}', [RequestController::class, 'updateStatus'])->name('update-status');
        Route::get('/{request}', [RequestController::class, 'show'])->name('show');
    });

    Route::middleware('condition.is.active:manage lines')->group(function () {
        Route::get('/change-logs', [ChangeLogController::class, 'index'])->name('change-logs.index'); 
        Route::get('/lines/for-sale', [LineController::class, 'forSaleList'])->name('lines.for-sale');
        Route::post('/lines/mark-for-sale', [LineController::class, 'markForSale'])->name('lines.mark-for-sale');
    });
    
    // Accounting Routes
    Route::middleware('condition.is.active:manage accounting')->group(function () {
        Route::get('/accounting/dashboard', [\App\Http\Controllers\AccountingController::class, 'dashboard'])->name('accounting.dashboard');
        Route::post('/accounting/capital', [\App\Http\Controllers\AccountingController::class, 'storeCapital'])->name('accounting.capital.store');
        Route::post('/accounting/expense', [\App\Http\Controllers\AccountingController::class, 'storeExpense'])->name('accounting.expense.store');
        Route::post('/accounting/direct-sale', [\App\Http\Controllers\AccountingController::class, 'storeDirectSale'])->name('accounting.direct-sale.store');
    });

    // HR Routes
    Route::middleware('condition.is.active:manage hr')->group(function () {
        Route::get('/hr/dashboard', [\App\Http\Controllers\HRController::class, 'dashboard'])->name('hr.dashboard');
        Route::post('/hr/advance', [\App\Http\Controllers\HRController::class, 'storeAdvance'])->name('hr.advance.store');
        Route::post('/hr/bonus', [\App\Http\Controllers\HRController::class, 'storeBonus'])->name('hr.bonus.store');
        Route::post('/hr/salary/pay', [\App\Http\Controllers\HRController::class, 'paySalary'])->name('hr.salary.pay');
    // HR Targets CRUD
    Route::post('/hr/target', [HRController::class, 'storeTarget'])->name('hr.target.store');
    Route::put('/hr/target/{target}', [HRController::class, 'updateTarget'])->name('hr.target.update');
    Route::delete('/hr/target/{target}', [HRController::class, 'destroyTarget'])->name('hr.target.destroy');

    // Notifications
    Route::get('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::get('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::post('/hr/target/assign', [\App\Http\Controllers\HRController::class, 'assignTarget'])->name('hr.target.assign');
        Route::post('/hr/target/release', [\App\Http\Controllers\HRController::class, 'releaseTargetBonus'])->name('hr.target.release');
        Route::get('/hr/advance-check', [\App\Http\Controllers\HRController::class, 'checkAdvanceAjax']);
    });
Route::get('/ajax/customer-by-nid', function (\Illuminate\Http\Request $request) {
    $nid = $request->q;
    $customer = \App\Models\Customer::where('national_id', $nid)->first();

    return $customer ? response()->json($customer) : response()->json(null, 404);
});



});
require __DIR__.'/auth.php';
