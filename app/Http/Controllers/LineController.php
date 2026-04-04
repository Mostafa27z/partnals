<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Line;
use App\Models\Plan;
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

public function importProcess(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx'
    ]);

    $rows = Excel::toCollection(null, $request->file('file'))->first();
    $count = 0;
    $errors = [];
    $failedRows = [];

    $validProviders = ['Vodafone', 'Etisalat', 'Orange', 'WE'];

    foreach ($rows as $index => $row) {
        if ($index === 0) continue;

        $rowNumber = $index + 1;
        $planName   = trim($row[0] ?? '');
        $phone      = trim($row[1] ?? '');
        $provider   = trim($row[2] ?? '');
        $fullName   = trim($row[3] ?? '');
        $nationalId = trim($row[4] ?? '');

        $error = null;

        // Required fields validation
        if (!$planName) {
            $error = "النظام مطلوب.";
        } elseif (!$phone) {
            $error = "رقم الهاتف مطلوب.";
        } elseif (!preg_match('/^\d{11}$/', $phone)) {
            $error = "رقم الهاتف يجب أن يكون 11 رقم.";
        } elseif (Line::where('phone_number', $phone)->exists()) {
            $error = "رقم الهاتف $phone مستخدم بالفعل.";
        } elseif (!$provider || !in_array($provider, $validProviders)) {
            $error = "مزود الخدمة غير صالح ($provider).";
        }

        // Plan validation
        $plan = Plan::where('name', $planName)->first();
        if (!$plan) {
            $error = "النظام '$planName' غير موجود.";
        }

        // Capture the error
        if ($error) {
            $failedRows[] = [
                'النظام' => $planName,
                'رقم الهاتف' => $phone,
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

        // Determine last_invoice_date based on provider
        $today = now();
        $day = match ($provider) {
            'Vodafone' => 10,
            'Etisalat', 'WE' => 1,
            'Orange' => 15,
            default => 1,
        };
        $lastInvoiceDate = $today->copy()->day($day)->startOfDay();

        // Create the line
        Line::create([
            'phone_number'       => $phone,
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
                return ['النظام', 'رقم الهاتف', 'المزود', 'الاسم', 'الرقم القومي', 'الخطأ'];
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
    $query = Line::with(['customer', 'plan']);

    if ($request->filled('phone')) {
        $query->where('phone_number', 'like', '%' . $request->phone . '%');
    }

    if ($request->filled('distributor')) {
        $query->where('distributor', 'like', '%' . $request->distributor . '%');
    }

    if ($request->filled('provider')) {
        $query->where('provider', $request->provider);
    }

    if ($request->filled('plan_id')) {
        $query->where('plan_id', $request->plan_id);
    }

    if ($request->filled('gcode')) {
        $query->where('gcode', $request->gcode);
    }

    if ($request->filled('nid')) {
        $query->whereHas('customer', function ($q) use ($request) {
            $q->where('national_id', 'like', '%' . $request->nid . '%');
        });
    }

    $lines = $query->latest()->paginate(20);
    $plans = \App\Models\Plan::select('id', 'name')->get();

    return view('admin.lines.all', compact('lines', 'plans'));
}


    public function index(Customer $customer)
    {
        $lines = $customer->lines()->with('plan')->get();
        return view('admin.lines.index', compact('customer', 'lines'));
    }

    public function create(Customer $customer)
{
    $providers = ['Vodafone', 'Etisalat', 'Orange', 'WE'];
    $plans = Plan::all(); // كل الخطط مبدئيًا
    return view('admin.lines.create', compact('customer', 'plans', 'providers'));
}


    public function store(Request $request, Customer $customer)
{
    $validated = $request->validate($this->rules());

    $full_number = $validated['phone_number'];
    $exists = Line::whereRaw("CONCAT(gcode, phone_number) = ?", [$full_number])->exists();

    if ($exists) {
        return back()->withErrors(['phone_number' => 'رقم الهاتف هذا مستخدم بالفعل'])->withInput();
    }

    // ✅ تحديد تاريخ آخر فاتورة حسب مزود الخدمة
    $provider = $validated['provider'];
    $now = now();
    $currentMonth = $now->month;
    $currentYear = $now->year;

    $invoiceDate = match ($provider) {
        'Vodafone' => now()->setDay(10)->setMonth($currentMonth)->setYear($currentYear),
        'Etisalat', 'WE' => now()->setDay(1)->setMonth($currentMonth)->setYear($currentYear),
        'Orange' => now()->setDay(15)->setMonth($currentMonth)->setYear($currentYear),
        default => null
    };

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
    $plans = Plan::where('provider', $line->provider)->get();
    return view('admin.lines.edit', compact('customer', 'line', 'plans'));
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
    }

    $line->update($validated);

    return redirect()->route('customers.show', $customer)->with('success', 'تم تعديل بيانات الخط');
}


    public function destroy(Customer $customer, Line $line)
    {
        $line->delete();
        return redirect()->route('customers.show', $customer)->with('success', 'تم حذف الخط');
    }

    public function createStandalone()
{
    $customers = Customer::all();
    $plans = Plan::all(); // كل الخطط مبدئيًا
    $providers = ['Vodafone', 'Etisalat', 'Orange', 'WE'];
    return view('admin.lines.create', compact('plans', 'customers', 'providers'));
}

    public function storeStandalone(Request $request)
{
    $validated = $request->validate(array_merge($this->rules(), [
        'phone_number'      => 'required|unique:lines|size:11',
        'plan_id'           => 'required|exists:plans,id',
        'gcode'             => 'required|in:010,011,012,015',
        'provider'          => 'required|in:Vodafone,Etisalat,Orange,WE',
        'line_type'         => 'required|in:prepaid,postpaid',
    ]));

    // تحديد أو إنشاء العميل
    $customerId = null;
    if ($request->filled('existing_customer_id')) {
        $customer = Customer::find($request->existing_customer_id);
        if ($request->has('update_customer_data')) {
            $customer->update([
                'full_name'  => $request->full_name,
                'email'      => $request->email,
                'birth_date' => $request->birth_date,
                'address'    => $request->address,
            ]);
        }

        $customerId = $customer->id;
    } elseif ($request->filled(['new_full_name', 'new_national_id'])) {
        $customer = Customer::create([
    'full_name'   => $request->new_full_name,
    'national_id' => $request->new_national_id,
    'email'       => $request->email,
    'birth_date'  => $request->birth_date,
    'address'     => $request->address,
]);

        $customerId = $customer->id;
    }

    Line::create([
        'phone_number'       => $validated['phone_number'],
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
        'distributor'        => $request->distributor,
    ]);

    return redirect()->route('lines.all')->with('success', '✅ تم إضافة الخط بنجاح.');
}


public function show(Line $line)
{
    return view('admin.lines.show', compact('line'));
}

    public function editStandalone(Line $line)
{
    $customers = Customer::all();
    $plans = Plan::where('provider', $line->provider)->get();

    return view('admin.lines.edit', [
        'line' => $line,
        'plans' => $plans,
        'customers' => $customers,
        'customer' => $line->customer,
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
    $validated = $request->validate([
        'gcode' => 'required|in:010,011,012,015',
        'distributor' => 'nullable|string|max:255',
        'provider' => 'required|in:Vodafone,Etisalat,Orange,WE',
        'line_type' => 'required|in:prepaid,postpaid',
        'plan_id' => 'nullable|exists:plans,id',
        'package' => 'nullable|string|max:255',
        'last_invoice_date' => 'nullable|date',
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

    $line->update([
        'gcode' => $validated['gcode'],
        'distributor' => $validated['distributor'],
        'provider' => $validated['provider'],
        'line_type' => $validated['line_type'],
        'plan_id' => $validated['plan_id'],
        'package' => $validated['package'],
        'last_invoice_date' => $validated['last_invoice_date'],
        'notes' => $validated['notes'],
        'customer_id' => $customerId,
        'attached_at' => $line->customer_id != $customerId ? now() : $line->attached_at
    ]);

    return redirect()->route('lines.all')->with('success', 'تم تحديث بيانات الخط بنجاح');
}



    public function destroyStandalone(Line $line)
{
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

        return [
            'gcode'        => 'required|in:010,011,012,015',
            'phone_number' => ['required', 'digits:11', 'regex:/^[0-9]/', $uniqueRule],
            'provider'     => 'required|in:Vodafone,Etisalat,Orange,WE',
            'line_type'    => 'required|in:prepaid,postpaid',
            'plan_id'      => 'nullable|exists:plans,id',
            'last_invoice_date' => 'nullable|date',
            'package'      => 'nullable|string|max:100',
            'notes'        => 'nullable|string|max:255',
        ];
    }

    private function expectedProviders()
    {
        return [
            '010' => 'Orange',
            '011' => 'Etisalat',
            '015' => 'WE',
            '012' => 'Vodafone',
        ];
    }
    public function forSaleList()
{
      // ->whereNull('deleted_at')
    $lines = Line::with('customer')->paginate(10);

    return view('admin.lines.for-sale', compact('lines'));
}

public function markForSale(Request $request)
{
    foreach ($request->input('lines', []) as $lineId => $data) {
        $line = Line::find($lineId);

        if (!$line) continue;

        $isSelected = isset($data['selected']);

        $line->for_sale = $isSelected;

        if ($isSelected) {
            $line->sale_price = $data['sale_price'] ?? null;
        } else {
            $line->sale_price = null;
        }

        $line->save();
    }

    return back()->with('success', '✅ تم تحديث حالة البيع للخطوط بنجاح.');
}


}
