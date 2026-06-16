<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Line;
use App\Models\Plan;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\LinesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use App\Exports\SelectedLinesExport;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Illuminate\Support\Facades\Storage;
class LineController extends Controller
{
    public function importForm()
{
    return view('admin.lines.import');
}




public function exportSelected(Request $request)
{
    $ids = $request->input('selected_lines', []);

    if (empty($ids)) {
        return back()->with('error', '❌ لم يتم تحديد أي خطوط للتصدير.');
    }

    $lines = \App\Models\Line::with('customer', 'plan')->whereIn('id', $ids)->get();

    return Excel::download(new SelectedLinesExport($lines), 'selected_lines.xlsx');
}

public function bulkDelete(Request $request)
{
    abort_unless(auth()->user()->hasPermission('delete line'), 403);
    $ids = $request->input('selected_lines', []);

    if (empty($ids)) {
        return back()->with('error', '❌ لم يتم تحديد أي خطوط للحذف.');
    }

    \App\Models\Line::whereIn('id', $ids)->delete(); // Soft delete

    return back()->with('success', '✅ تم حذف الخطوط المحددة بنجاح.');
}

public function importProcess(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx'
    ]);

    $rows = Excel::toCollection(null, $request->file('file'))->first();
    $count = 0;
    $errors = [];
    $failedRows = [];

    $validProviders = Provider::pluck('name')->toArray();
    $providersMap   = Provider::pluck('invoice_day', 'name')->toArray();

    foreach ($rows as $index => $row) {
        if ($index === 0) continue;

        $rowNumber = $index + 1;
        $phone      = trim($row[0] ?? '');
        $planName   = trim($row[1] ?? '');
        $provider   = trim($row[2] ?? '');
        $fullName   = trim($row[3] ?? '');
        $nationalId = trim($row[4] ?? '');
        $gcode      = substr($phone, 0, 3);

        $error = null;

        // Required fields validation
        if (!$phone) {
            $error = "رقم الهاتف مطلوب.";
        } elseif (!preg_match('/^\d{11}$/', $phone)) {
            $error = "رقم الهاتف يجب أن يكون 11 رقم.";
        } elseif (Line::where('phone_number', $phone)->exists()) {
            $error = "رقم الهاتف $phone مستخدم بالفعل.";
        } elseif (!$planName) {
            $error = "النظام مطلوب.";
        } elseif (!$provider || !in_array($provider, $validProviders)) {
            $error = "مزود الخدمة غير صالح ($provider).";
        } elseif (!in_array($gcode, Line::allowedGcodes())) {
            $error = "مقدمة الرقم غير صحيحة ($gcode).";
        } else {
            $expectedProvider = Line::providerForGcode($gcode);
            if ($expectedProvider && $provider !== $expectedProvider) {
                $error = "مزود الخدمة يجب أن يكون $expectedProvider للمقدمة $gcode.";
            }
        }

        // Plan validation
        $plan = Plan::where('name', $planName)->first();
        if (!$plan && !$error) {
            $error = "النظام '$planName' غير موجود.";
        }

        // Capture the error
        if ($error) {
            $failedRows[] = [
                'رقم الهاتف' => $phone,
                'النظام' => $planName,
                'المزود' => $provider,
                'الاسم' => $fullName,
                'الرقم القومي' => $nationalId,
                'الخطأ' => $error
            ];
            continue;
        }

        // Handle customer creation if needed
        $customerId = null;
        if ($nationalId) {
            $customer = Customer::where('national_id', $nationalId)->first();
            if (!$customer && $fullName) {
                $customer = Customer::create([
                    'full_name'   => $fullName,
                    'national_id' => $nationalId,
                ]);
            }
            $customerId = $customer?->id;
        }

        // Determine last_invoice_date based on provider from database
        $today = now();
        $invoiceDay = $providersMap[$provider] ?? 1;
        $lastInvoiceDate = $today->copy()->day($invoiceDay)->startOfDay();

        // Create the line
        Line::create([
            'phone_number'       => $phone,
            'gcode'              => substr($phone, 0, 3),
            'provider'           => $provider,
            'plan_id'            => $plan->id,
            'customer_id'        => $customerId,
            'last_invoice_date'  => $lastInvoiceDate,
            'added_by'           => Auth::id(),
        ]);

        $count++;
    }

    // Export failed rows if any
    if (count($failedRows)) {
        $filename = 'import_errors_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new class($failedRows) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $rows;

            public function __construct($rows)
            {
                $this->rows = collect($rows);
            }

            public function collection()
            {
                return $this->rows;
            }

            public function headings(): array
            {
                return ['رقم الهاتف', 'النظام', 'المزود', 'الاسم', 'الرقم القومي', 'الخطأ'];
            }
        }, $filename);
    }

    return redirect()->route('lines.all')->with('success', "✅ تم استيراد $count خط بنجاح.");
}

    public function export()
    {
        return Excel::download(new LinesExport, 'lines.xlsx');
    }

    public function all(Request $request) 
{ 
    $plans = \App\Models\Plan::select('id', 'name')->get();
    $user = Auth::user();
    $isDistributor = $user->role && $user->role->name === 'موزع';

    $distributors = \App\Models\User::whereHas('role', function($q) {
        $q->where('name', 'موزع');
    })->when($isDistributor, function($q) use ($user) {
        $q->where('id', $user->id);
    })->select('id', 'name')->get();

    $hasSearch = $request->hasAny(['phone', 'distributor_id', 'provider', 'plan_id', 'gcode', 'nid', 'last_invoice_from', 'last_invoice_to']);

    if (!$hasSearch) {
        $lines = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        return view('admin.lines.all', compact('lines', 'plans', 'distributors', 'hasSearch'));
    }

    $query = Line::with(['customer', 'plan', 'distributor']);
    
    if ($isDistributor) {
        $query->where('distributor_id', $user->id);
    }

    $query = $this->applyFilters($query, $request);

    $totalCount = $query->count();
    $lines = $query->latest()->paginate(20);

    return view('admin.lines.all', compact('lines', 'plans', 'distributors', 'hasSearch', 'totalCount'));
}

public function bulkDistributorsIndex(Request $request) 
{ 
    $user = Auth::user();
    if ($user->role && $user->role->name === 'موزع') {
        abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة.');
    }

    $plans = \App\Models\Plan::select('id', 'name')->get();
    $isDistributor = false;

    $distributors = \App\Models\User::whereHas('role', function($q) {
        $q->where('name', 'موزع');
    })->when($isDistributor, function($q) use ($user) {
        $q->where('id', $user->id);
    })->select('id', 'name')->get();

    $hasSearch = $request->hasAny(['phone', 'distributor_id', 'provider', 'plan_id', 'gcode', 'nid', 'last_invoice_from', 'last_invoice_to']);

    if (!$hasSearch) {
        $lines = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        return view('admin.lines.bulk-distributors', compact('lines', 'plans', 'distributors', 'hasSearch'));
    }

    $query = Line::with(['customer', 'plan', 'distributor']);
    
    if ($isDistributor) {
        $query->where('distributor_id', $user->id);
    }

    $query = $this->applyFilters($query, $request);

    $totalCount = $query->count();
    $lines = $query->latest()->paginate(20);

    return view('admin.lines.bulk-distributors', compact('lines', 'plans', 'distributors', 'hasSearch', 'totalCount'));
}

public function deleteIndex(Request $request) 
{ 
    abort_unless(auth()->user()->hasPermission('delete line'), 403);
    $plans = \App\Models\Plan::select('id', 'name')->get();
    $user = Auth::user();
    $isDistributor = $user->role && $user->role->name === 'موزع';

    $distributors = \App\Models\User::whereHas('role', function($q) {
        $q->where('name', 'موزع');
    })->when($isDistributor, function($q) use ($user) {
        $q->where('id', $user->id);
    })->select('id', 'name')->get();

    $hasSearch = $request->hasAny(['phone', 'distributor_id', 'provider', 'plan_id', 'gcode', 'nid', 'last_invoice_from', 'last_invoice_to']);

    if (!$hasSearch) {
        $lines = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        return view('admin.lines.delete', compact('lines', 'plans', 'distributors', 'hasSearch'));
    }

    $query = Line::with(['customer', 'plan', 'distributor']);
    
    if ($isDistributor) {
        $query->where('distributor_id', $user->id);
    }

    $query = $this->applyFilters($query, $request);

    $totalCount = $query->count();
    $lines = $query->latest()->paginate(20);

    return view('admin.lines.delete', compact('lines', 'plans', 'distributors', 'hasSearch', 'totalCount'));
}

public function bulkUpdateDistributor(Request $request)
{
    $ids = $request->input('selected_lines', []);
    $distributor_id = $request->input('bulk_distributor_id');
    $applyToAll = $request->has('apply_to_all');
    $action = $request->input('bulk_action'); // 'assign' or 'remove'

    if ($action === 'remove' || $action === 'assign') {
        if (auth()->user()->role?->name !== 'admin') {
            return back()->with('error', '❌ غير مصرح لك بتغيير الموزع.');
        }
    }

    if ($action === 'remove') {
        $distributor_id = null;
    } elseif ($action === 'assign' && !$distributor_id) {
        return back()->with('error', '❌ يرجى اختيار موزع للتعيين.');
    }

    $query = Line::query();

    if ($applyToAll) {
        $query = $this->applyFilters($query, $request);
    } else {
        if (empty($ids)) {
            return back()->with('error', '❌ لم يتم تحديد أي خطوط.');
        }
        $query->whereIn('id', $ids);
    }

    $count = $query->count();
    $query->update(['distributor_id' => $distributor_id]);

    return back()->with('success', "✅ تم تحديث الموزع لـ $count خط بنجاح.");
}

private function applyFilters($query, Request $request)
{
    if ($request->filled('phone')) {
        $query->where('phone_number', 'like', '%' . $request->phone . '%');
    }

    if ($request->has('distributor_id')) {
        if ($request->distributor_id === 'none') {
            $query->whereNull('distributor_id');
        } elseif ($request->filled('distributor_id')) {
            // If the user is a distributor, they can only filter by their own ID anyway
            // but we enforce it here just in case.
            if (auth()->user()->role?->name === 'موزع') {
                $query->where('distributor_id', auth()->id());
            } else {
                $query->where('distributor_id', $request->distributor_id);
            }
        }
    }

    if ($request->filled('provider')) {
        $query->where('provider', $request->provider);
    }

    if ($request->filled('plan_id')) {
        $query->where('plan_id', $request->plan_id);
    }

    if ($request->filled('last_invoice_from')) {
        $query->whereDate('last_invoice_date', '>=', $request->last_invoice_from);
    }

    if ($request->filled('last_invoice_to')) {
        $query->whereDate('last_invoice_date', '<=', $request->last_invoice_to);
    }

    if ($request->filled('gcode')) {
        $query->where('gcode', $request->gcode);
    }

    if ($request->filled('nid')) {
        $query->whereHas('customer', function ($q) use ($request) {
            $q->where('national_id', 'like', '%' . $request->nid . '%');
        });
    }

    return $query;
}


    public function index(Customer $customer)
    {
        $user = auth()->user();
        $isDistributor = $user->role && $user->role->name === 'موزع';

        $query = $customer->lines()->with('plan');
        
        if ($isDistributor) {
            $query->where('distributor_id', $user->id);
        }

        $lines = $query->get();
        return view('admin.lines.index', compact('customer', 'lines'));
    }

    public function create(Customer $customer)
{
    $user = Auth::user();
    $isDistributor = $user->role && $user->role->name === 'موزع';

    $providers = Provider::all();
    $plans = Plan::all(); // كل الخطط مبدئيًا
    $distributors = \App\Models\User::whereHas('role', function($q) {
        $q->where('name', 'موزع');
    })->when($isDistributor, function($q) use ($user) {
        $q->where('id', $user->id);
    })->select('id', 'name')->get();
    return view('admin.lines.create', compact('customer', 'plans', 'providers', 'distributors'));
}


    public function store(Request $request, Customer $customer)
{
    $validated = $request->validate($this->rules());

    $full_number = $validated['phone_number'];
    $exists = Line::whereRaw("CONCAT(gcode, phone_number) = ?", [$full_number])->exists();

    if ($exists) {
        return back()->withErrors(['phone_number' => 'رقم الهاتف هذا مستخدم بالفعل'])->withInput();
    }

    // ✅ تحديد تاريخ آخر فاتورة حسب مزود الخدمة من القاعدة
    $dbProvider = \App\Models\Provider::where('name', $validated['provider'])->first();
    $invoiceDay = $dbProvider ? $dbProvider->invoice_day : 1;
    
    $now = now();
    $invoiceDate = now()->setDay($invoiceDay)->setMonth($now->month)->setYear($now->year)->startOfDay();

    $lineData = array_merge($validated, [
        'added_by' => Auth::id(),
        'attached_at' => now(),
        'last_invoice_date' => $invoiceDate
    ]);

    $customer->lines()->create($lineData);

    if ($request->has('save_and_add_more')) {
        return redirect()->route('lines.create')->with('success', '✅ تم حفظ الخط بنجاح، يمكنك إضافة خط آخر.');
    }

    return redirect()->route('lines.all')->with('success', '✅ تم إضافة الخط بنجاح.');
}


    public function edit(Customer $customer, Line $line)
{
    $user = Auth::user();
    $isDistributor = $user->role && $user->role->name === 'موزع';

    $providers = Provider::all();
    $plans = Plan::all(); // Send all plans to allow dynamic switching
    $distributors = \App\Models\User::whereHas('role', function($q) {
        $q->where('name', 'موزع');
    })->when($isDistributor, function($q) use ($user) {
        $q->where('id', $user->id);
    })->select('id', 'name')->get();
    return view('admin.lines.edit', compact('customer', 'line', 'plans', 'providers', 'distributors'));
}


   public function update(Request $request, Customer $customer, Line $line) 
{
    $validated = $request->validate($this->rules($line->id));

    $full_number = $validated['phone_number'];
    $exists = Line::whereRaw("CONCAT(gcode, phone_number) = ?", [$full_number])
                  ->where('id', '!=', $line->id)->exists();

    if ($exists) {
        return back()->withErrors(['phone_number' => 'رقم الهاتف هذا مستخدم بالفعل'])->withInput();
    }

    // إذا تغيّر العميل، حدّث تاريخ الربط
    if (array_key_exists('customer_id', $validated) && $validated['customer_id'] != $line->customer_id) {
        $validated['attached_at'] = now();

        if ($request->input('transfer_invoices') == '1') {
            \App\Models\Invoice::where('line_id', $line->id)->update(['customer_id' => $validated['customer_id']]);
        }
    }

    $line->update($validated);

    return redirect()->route('customers.show', $customer)->with('success', 'تم تعديل بيانات الخط');
}


    public function destroy(Customer $customer, Line $line)
    {
        abort_unless(auth()->user()->hasPermission('delete line'), 403);
        $line->delete();
        return redirect()->route('customers.show', $customer)->with('success', 'تم حذف الخط');
    }

    public function createStandalone(Request $request)
{
    $user = Auth::user();
    $isDistributor = $user->role && $user->role->name === 'موزع';

    $customers = Customer::all();
    $plans = Plan::all(); // كل الخطط مبدئيًا
    $providers = Provider::all();
    $distributors = \App\Models\User::whereHas('role', function($q) {
        $q->where('name', 'موزع');
    })->when($isDistributor, function($q) use ($user) {
        $q->where('id', $user->id);
    })->select('id', 'name')->get();

    $selectedCustomer = null;
    if ($request->filled('customer_id')) {
        $selectedCustomer = Customer::find($request->input('customer_id'));
    }

    return view('admin.lines.create', compact('plans', 'customers', 'providers', 'distributors', 'selectedCustomer'));
}

    public function storeStandalone(Request $request)
{
    $validProvidersStr = Provider::pluck('name')->implode(',');
    $validated = $request->validate(array_merge($this->rules(), [
        'phone_number'      => 'required|unique:lines|size:11',
        'plan_id'           => 'required|exists:plans,id',
        'gcode'             => 'required|in:010,011,012,015',
        'provider'          => 'required|in:' . $validProvidersStr,
        'line_type'         => 'required|in:prepaid,postpaid',
    ]));

    // تحديد أو إنشاء العميل
    $customerId = null;
    $hasExistingId = $request->filled('existing_customer_id') && is_numeric($request->existing_customer_id);

    if ($hasExistingId) {
        $customer = Customer::find($request->existing_customer_id);
        if ($customer) {
            if ($request->has('update_customer_data')) {
                $customer->update([
                    'full_name'  => $request->full_name,
                    'email'      => $request->email,
                    'birth_date' => $request->birth_date,
                    'address'    => $request->address,
                ]);
            }
            $customerId = $customer->id;
        }
    } 
    
    // If no existing customer found or provided, try creating a new one if data is present
    if (!$customerId && $request->filled(['full_name', 'national_id'])) {
        $customer = Customer::create([
            'full_name'   => $request->full_name,
            'national_id' => $request->national_id,
            'email'       => $request->email,
            'birth_date'  => $request->birth_date,
            'address'     => $request->address,
        ]);

        $customerId = $customer->id;
    }

    Line::create([
        'phone_number'       => $validated['phone_number'],
        'serial_number'      => $request->serial_number,
        'gcode'              => $validated['gcode'],
        'provider'           => $validated['provider'],
        'line_type'          => $validated['line_type'],
        'plan_id'            => $validated['plan_id'],
        'customer_id'        => $customerId,
        'added_by'           => Auth::id(),
        'attached_at'        => now(),
        'last_invoice_date'       => $request->last_invoice_date,
        'package'            => $request->package,
        'notes'              => $request->notes,
        'distributor_id'     => $request->distributor_id,
        'buy_price'          => $request->buy_price,
        'sale_price'         => $request->sale_price,
    ]);

    return redirect()->route('lines.all')->with('success', '✅ تم إضافة الخط بنجاح.');
}


public function show(Line $line)
{
    $user = auth()->user();
    $isDistributor = $user->role && $user->role->name === 'موزع';

    if ($isDistributor && $line->distributor_id !== $user->id) {
        abort(403, 'غير مصرح لك بمشاهدة هذا الخط.');
    }

    $line->load(['addedBy', 'customer', 'plan', 'providerData']);

    $requests = $line->requests()
        ->with(['requestedBy', 'doneBy', 'resellDetails', 'changePlan', 'changeChip', 'pause', 'resume', 'changeDistributor', 'changeDate'])
        ->latest()
        ->paginate(15);

    return view('admin.lines.show', compact('line', 'requests'));
}

    public function editStandalone(Line $line)
{
    $user = Auth::user();
    $isDistributor = $user->role && $user->role->name === 'موزع';

    $customers = Customer::all();
    $providers = Provider::all();
    $plans = Plan::all(); // Send all plans to allow dynamic switching

    return view('admin.lines.edit', [
        'line' => $line,
        'plans' => $plans,
        'customers' => $customers,
        'customer' => $line->customer,
        'providers' => $providers,
        'distributors' => \App\Models\User::whereHas('role', function($q) {
            $q->where('name', 'موزع');
        })->when($isDistributor, function($q) use ($user) {
            $q->where('id', $user->id);
        })->select('id', 'name')->get(),
    ]);
}

// search
// public function search(Request $request)
// {
//     $term = $request->q;

//     $customers = Customer::where('full_name', 'like', "%$term%")
//         ->orWhere('national_id', 'like', "%$term%")
//         ->select('id', 'full_name', 'national_id')
//         ->limit(20)
//         ->get();

//     return response()->json($customers);
// }
public function updateStandalone(Request $request, Line $line)
{
    $validProvidersStr = Provider::pluck('name')->implode(',');
    $validated = $request->validate([
        'gcode' => 'required|in:010,011,012,015',
        'serial_number' => 'nullable|string|max:255',
        'distributor_id' => 'nullable|exists:users,id',
        'provider' => 'required|in:' . $validProvidersStr,
        'line_type' => 'required|in:prepaid,postpaid',
        'plan_id' => 'nullable|exists:plans,id',
        'package' => 'nullable|string|max:255',
        'last_invoice_date' => 'nullable|date',
        'payment_date' => 'nullable|date',
        'notes' => 'nullable|string',
        'national_id' => 'nullable|string|size:14',
        'full_name' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',
        'birth_date' => 'nullable|date',
        'address' => 'nullable|string|max:255',
        'existing_customer_id' => 'nullable|exists:customers,id',
        'update_customer_data' => 'sometimes|accepted',
    ]);

    $shouldUpdateCustomer = $request->boolean('update_customer_data');
    $customerData = [
        'full_name' => $validated['full_name'] ?? null,
        'national_id' => $validated['national_id'] ?? null,
        'email' => $validated['email'] ?? null,
        'birth_date' => $validated['birth_date'] ?? null,
        'address' => $validated['address'] ?? null
    ];

    $customerId = $line->customer_id;

    if (!empty($validated['national_id'])) {
        if ($request->filled('existing_customer_id')) {
            $customerId = $request->existing_customer_id;

            if ($shouldUpdateCustomer) {
                Customer::where('id', $customerId)->update(array_filter($customerData, fn($v) => $v !== null));
            }
        } else {
            $existingCustomer = Customer::where('national_id', $validated['national_id'])->first();

            if ($existingCustomer) {
                $customerId = $existingCustomer->id;

                if ($shouldUpdateCustomer) {
                    $existingCustomer->update(array_filter($customerData, fn($v) => $v !== null));
                }
            } elseif (!empty($validated['full_name'])) {
                $newCustomer = Customer::create(array_filter($customerData, fn($v) => $v !== null));
                $customerId = $newCustomer->id;
            }
        }
    }

    $isAdmin = auth()->user()->role && auth()->user()->role->name === 'admin';
    
    $updateData = [
        'gcode' => $validated['gcode'],
        'serial_number' => $validated['serial_number'] ?? null,
        'provider' => $validated['provider'],
        'line_type' => $validated['line_type'],
        'plan_id' => $validated['plan_id'],
        'package' => $validated['package'],
        'payment_date' => $validated['payment_date'] ?? null,
        'notes' => $validated['notes'],
        'buy_price' => $request->buy_price,
        'sale_price' => $request->sale_price,
        'customer_id' => $customerId,
        'attached_at' => $line->customer_id != $customerId ? now() : $line->attached_at
    ];

    // Only allow admins to update these specific fields
    if ($isAdmin) {
        $updateData['distributor_id'] = $validated['distributor_id'] ?? null;
        $updateData['last_invoice_date'] = $validated['last_invoice_date'];
    }

    $oldCustomerId = $line->customer_id;
    $line->update($updateData);

    if ($oldCustomerId != $customerId && $request->input('transfer_invoices') == '1') {
        \App\Models\Invoice::where('line_id', $line->id)->update(['customer_id' => $customerId]);
    }

    return redirect()->route('lines.all')->with('success', 'تم تحديث بيانات الخط بنجاح');
}



    public function destroyStandalone(Line $line)
{
    abort_unless(auth()->user()->hasPermission('delete line'), 403);
    $line->delete(); // soft delete instead of force delete
    return redirect()->route('lines.all')->with('success', '✅ تم حذف الخط مؤقتًا.');
}
public function trashed()
{
    $lines = Line::onlyTrashed()->with('customer')->paginate(20);
    return view('admin.lines.trashed', compact('lines'));
}

public function forceDelete($id)
{
    abort_unless(auth()->user()->hasPermission('delete line'), 403);
    $line = Line::onlyTrashed()->findOrFail($id);
    $line->forceDelete();

    return back()->with('success', '🗑️ تم حذف الخط نهائيًا.');
}

public function restore($id)
{
    $line = Line::onlyTrashed()->findOrFail($id);
    $line->restore();

    return redirect()->route('lines.all')->with('success', '✅ تم استرجاع الخط بنجاح.');
}


    private function rules($id = null)
    {
        $uniqueRule = 'unique:lines,phone_number';
        if ($id) {
            $uniqueRule .= "," . $id;
        }

        $validProvidersStr = Provider::pluck('name')->implode(',');

        return [
            'gcode'        => 'required|in:' . implode(',', Line::allowedGcodes()),
            'phone_number' => [
                'required',
                'digits:11',
                'regex:/^[0-9]+$/',
                $uniqueRule,
                function ($attribute, $value, $fail) {
                    $gcode = request('gcode');
                    if ($gcode && !str_starts_with($value, $gcode)) {
                        $fail(app()->getLocale() === 'ar'
                            ? "رقم الهاتف يجب أن يبدأ بمقدمة الرقم المحددة ($gcode)."
                            : "Phone number must start with the selected prefix ($gcode).");
                    }
                }
            ],
            'provider'     => [
                'required',
                'in:' . $validProvidersStr,
                function ($attribute, $value, $fail) {
                    $gcode = request('gcode');
                    $expected = Line::providerForGcode($gcode);
                    if ($expected && $value !== $expected) {
                        $fail(app()->getLocale() === 'ar'
                            ? "مزود الخدمة يجب أن يكون $expected للمقدمة $gcode."
                            : "Provider must be $expected for prefix $gcode.");
                    }
                }
            ],
            'line_type'    => 'required|in:prepaid,postpaid',
            'plan_id'      => 'nullable|exists:plans,id',
            'last_invoice_date' => 'nullable|date',
            'payment_date'      => 'nullable|date',
            'package'      => 'nullable|string|max:100',
            'notes'        => 'nullable|string|max:255',
            'distributor_id' => 'nullable|exists:users,id',
            'buy_price'    => 'nullable|numeric|min:0',
            'sale_price'   => 'nullable|numeric|min:0',
        ];
    }

    public function forSaleList(Request $request)
{
    $user = auth()->user();
    $isDistributor = $user->role && $user->role->name === 'موزع';

    $plans = \App\Models\Plan::select('id', 'name')->get();
    $distributors = \App\Models\User::whereHas('role', function($q) {
        $q->where('name', 'موزع');
    })->when($isDistributor, function($q) use ($user) {
        $q->where('id', $user->id);
    })->select('id', 'name')->get();

    $query = Line::with(['customer', 'plan', 'distributor']);

    if ($isDistributor) {
        $query->where('distributor_id', $user->id);
    }
    
    $query = $this->applyFilters($query, $request);

    // البحث برقم الهاتف
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where('phone_number', 'LIKE', "%{$search}%");
    }

    $lines = $query->latest()->paginate(20)->withQueryString();

    return view('admin.lines.for-sale', compact('lines', 'plans', 'distributors'));
}

    public function markForSale(Request $request)
    {
        foreach ($request->input('lines', []) as $lineId => $data) {
            $line = Line::find($lineId);

            if (!$line) continue;

            $isSellDone = isset($data['sell_done']);
            $isSelected = isset($data['selected']);

            if ($isSellDone) {
                // بيع نهائي
                $line->is_sold = true;
                $line->for_sale = false; // يخرج من وضع العرض للبيع نظراً لبيعه
                $line->buy_price = $data['buy_price'] ?? 0;
                $line->sale_price = $data['sale_price'] ?? 0;
            } else {
                // إزالة البيع النهائي لو كان موجود خطأ
                $line->is_sold = false;
                $line->for_sale = $isSelected;
                
                if ($isSelected) {
                    $line->buy_price = $data['buy_price'] ?? null;
                    $line->sale_price = $data['sale_price'] ?? null;
                } else {
                    $line->buy_price = null;
                    $line->sale_price = null;
                }
            }

            $line->save();
        }

        return back()->with('success', '✅ تم تحديث حالة البيع للخطوط بنجاح.');
    }

    public function importForSale(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx',
        ]);

        $import = new \App\Imports\ForSaleLinesImport();
        \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));

        if (count($import->errorsList) > 0) {
            array_unshift($import->errorsList, [
                'رقم الهاتف', 'سعر الشراء', 'سعر البيع', 'الخطأ (Errors)'
            ]);

            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\InvoiceErrorsExport($import->errorsList),
                'for_sale_import_errors_' . now()->format('Ymd_His') . '.xlsx'
            );
        }

        return back()->with('success', '✅ تم استيراد وتحديث الخطوط المعروضة للبيع بنجاح.');
    }

    public function downloadForSaleSample()
    {
        $sampleData = [
            ['رقم الهاتف', 'سعر الشراء', 'سعر البيع'],
            ['01012345678', '150.00', '200.00'],
        ];

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\InvoiceErrorsExport($sampleData),
            'for_sale_lines_template.xlsx'
        );
    }

    // Export all lines marked for sale as Excel
    public function exportForSale(Request $request)
    {
        $filters = $request->only(['provider', 'plan_id']);

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\ForSaleLinesExport($filters),
            'lines_for_sale_' . now()->format('Ymd_His') . '.xlsx'
        );
    }


}
