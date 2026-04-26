<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Line;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RequestResumeLine;
use Carbon\Carbon;
 use App\Models\Plan;
class InvoiceController extends Controller
{
   

    public function create(Line $line)
{
    $line->load('plan', 'customer');

    // جلب آخر فاتورة للخط
    $lastInvoice = $line->last_invoice_date;

    // لو فيه فاتورة، نبدأ من الشهر التالي لتاريخها، لو مفيش نبدأ من الشهر الحالي
    $startDate = $lastInvoice
        ? \Carbon\Carbon::parse($lastInvoice)->addMonth()->startOfMonth()
        : now()->startOfMonth();

    return view('admin.invoices.create', compact('line', 'startDate'));
}


   public function store(Request $request, Line $line)
{
    $request->validate([
        'months_count' => 'required|integer|min:1',
    ]);

    $planPrice = optional($line->plan)->price ?? 0;
    $providerPrice = optional($line->plan)->provider_price ?? 0;
    $calculatedProfit = $planPrice - $providerPrice;
    
    $months = $request->months_count;

    // نأخذ اليوم من التاريخ الأخير أو اليوم الحالي
    $baseDate = $line->last_invoice_date
        ? Carbon::parse($line->last_invoice_date)->copy()->addMonth()
        : now();

    $day = $baseDate->day;
    $lastInvoiceMonth = null;

    for ($i = 0; $i < $months; $i++) {
        $invoiceDate = $baseDate->copy()->addMonths($i)->setDay($day);

        // لو اليوم غير صالح في هذا الشهر (مثلاً 31 فبراير)، يتم تصحيحه لآخر يوم متاح
        if (!$invoiceDate->isValid()) {
            $invoiceDate->day = $invoiceDate->daysInMonth;
        }

        Invoice::create([
            'line_id'           => $line->id,
            'amount'            => $planPrice,
            'calculated_profit' => $calculatedProfit,
            'invoice_month'     => $invoiceDate,
            'is_paid'           => true,
            'payment_date'      => now(),
            'paid_by'           => Auth::id(),
            'notes'             => $request->notes,
        ]);

        $lastInvoiceMonth = $invoiceDate;
    }

    if ($lastInvoiceMonth) {
        $line->update([
            'last_invoice_date' => $lastInvoiceMonth,
            'payment_date'      => now(),
            'for_sale'          => 0,
        ]);
    }

    // إعادة التشغيل إن تم دفع فاتورة مستقبلية
    $resumeExists = \App\Models\Request::where('line_id', $line->id)
        ->where('request_type', 'resume')
        ->whereDate('created_at', now()->toDateString())
        ->exists();

    if (
        $line->status === 'inactive' &&
        $lastInvoiceMonth &&
        $lastInvoiceMonth->greaterThan(now()) &&
        !$resumeExists
    ) {
        $resumeRequest = \App\Models\Request::create([
            'line_id'      => $line->id,
            'customer_id'  => $line->customer_id,
            'request_type' => 'resume',
            'status'       => 'pending',
            'requested_by' => Auth::id(),
        ]);

        \App\Models\RequestResumeLine::create([
            'request_id' => $resumeRequest->id,
            'reason'     => 'تم دفع الفاتورة',
            'comment'    => 'تم إنشاء الطلب بواسطة النظام تلقائياً',
        ]);
    }

    return redirect()->route('invoices.index', ['line' => $line->id])
                     ->with('success', '✅ تم دفع الفواتير بنجاح.');
}


public function index(Request $request)
{
    $query = Invoice::with(['line.customer', 'user'])
        ->whereHas('line');

    if ($request->filled('provider')) {
    $query->whereHas('line', fn($q) => $q->whereIn('provider', $request->provider));
}

if ($request->filled('line_type')) {
    $query->whereHas('line', fn($q) => $q->whereIn('line_type', $request->line_type));
}

if ($request->filled('plan_id')) {
    $query->whereHas('line', fn($q) => $q->whereIn('plan_id', $request->plan_id));
}

if ($request->filled('is_paid')) {
    $query->whereIn('is_paid', $request->is_paid);
}

if ($request->filled('from')) {
    $query->whereDate('invoice_month', '>=', $request->from);
}
if ($request->filled('to')) {
    $query->whereDate('invoice_month', '<=', $request->to);
}

if ($request->filled('paid_by')) {
    $query->whereIn('paid_by', $request->paid_by);
}

    $invoices = $query->latest('invoice_month')->paginate(20);
    $total = $query->sum('amount');

    $plans = Plan::all();
    $users = \App\Models\User::all();

    return view('admin.invoices.index', compact('invoices', 'total', 'plans', 'users'));
}


    public function customerInvoices(Request $request, Customer $customer) 
{ 
    $query = Invoice::whereHas('line', function ($q) use ($customer) { 
        $q->where('customer_id', $customer->id); 
    }); 
 
    if ($request->filled('provider')) { 
        $query->whereHas('line', fn($q) => $q->whereIn('provider', $request->provider)); 
    } 
 
    if ($request->filled('line_type')) { 
        $query->whereHas('line', fn($q) => $q->whereIn('line_type', $request->line_type)); 
    } 
 
    if ($request->filled('plan_id')) { 
        $query->whereHas('line', fn($q) => $q->whereIn('plan_id', $request->plan_id)); 
    } 
 
    if ($request->filled('is_paid')) { 
        $query->whereIn('is_paid', $request->is_paid); 
    } 
 
    if ($request->filled('from')) { 
        $query->whereDate('invoice_month', '>=', $request->from); 
    } 
 
    if ($request->filled('to')) { 
        $query->whereDate('invoice_month', '<=', $request->to); 
    } 
 
    $invoices = $query->with(['line', 'user'])->latest('invoice_month')->paginate(10); 
    $total = $query->sum('amount'); 
    $plans = Plan::select('id', 'name')->get(); // أو فقط Plan::all()

    return view('admin.invoices.customer', compact('customer', 'invoices', 'total', 'plans')); 
}



public function lineInvoices(Request $request, Line $line) 
{
    $query = $line->invoices()->with('user');

    if ($request->filled('provider')) {
        $query->whereHas('line', fn($q) => $q->whereIn('provider', $request->provider));
    }

    if ($request->filled('line_type')) {
        $query->whereHas('line', fn($q) => $q->whereIn('line_type', $request->line_type));
    }

    if ($request->filled('plan_id')) {
        $query->whereHas('line', fn($q) => $q->whereIn('plan_id', $request->plan_id));
    }

    if ($request->filled('is_paid')) {
        $query->whereIn('is_paid', $request->is_paid);
    }

    if ($request->filled('from')) {
        $query->whereDate('invoice_month', '>=', $request->from);
    }

    if ($request->filled('to')) {
        $query->whereDate('invoice_month', '<=', $request->to);
    }

    $invoices = $query->latest('invoice_month')->paginate(20);
    $total = $query->sum('amount');
    $plans = Plan::all(); // ✅ إضافة هذا السطر

    return view('admin.invoices.by-line', compact('line', 'invoices', 'total', 'plans'));
}


    public function downloadSample()
    {
        $sampleData = [
            ['رقم الهاتف', 'شهر البداية', 'السنة', 'عدد الشهور', 'المبلغ المدفوع الكلي', 'التكلفة الكلية'],
            ['25899911', '04', '2026', '4', '624.00', '499.20']
        ];
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\InvoiceErrorsExport($sampleData), 
            'invoices_import_sample.xlsx'
        );
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,csv,xls|max:5120',
        ]);

        $import = new \App\Imports\InvoicesImport();
        \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('excel_file'));

        if (count($import->errorsList) > 0) {
            array_unshift($import->errorsList, [
                'رقم الهاتف', 'شهر البداية', 'السنة', 'عدد الشهور', 'المبلغ المدفوع الكلي', 'التكلفة الكلية', 'الخطأ (Errors)'
            ]);
            
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\InvoiceErrorsExport($import->errorsList), 
                'invoices_import_errors_'.now()->format('Ymd_His').'.xlsx'
            );
        }

        return back()->with('success', 'تم استيراد ودفع الفواتير بنجاح!');
    }
}
