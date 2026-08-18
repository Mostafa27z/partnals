<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Plan;
use App\Models\Line;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isDistributor = $user->role && $user->role->name === 'موزع';

        $query = Line::withoutGlobalScope('distributor')
            ->with(['customer', 'distributor'])
            ->join('customers', 'lines.customer_id', '=', 'customers.id')
            ->select('lines.*')
            ->orderBy('customers.full_name', 'asc');

        if ($isDistributor) {
            $query->where('lines.distributor_id', $user->id);
        }

        if ($request->filled('name')) {
            $query->where('customers.full_name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('national_id')) {
            $query->where('customers.national_id', 'like', '%' . $request->national_id . '%');
        }

        if ($request->filled('phone_number')) {
            $query->where('lines.phone_number', 'like', '%' . $request->phone_number . '%');
        }

        $lines = $query->paginate(10);

        return view('admin.customers.index', compact('lines'));
    }

    public function create()
    {
        $plans = Plan::all();
        return view('admin.customers.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'national_id' => 'required|string|size:14|unique:customers,national_id',
            'phone_number' => 'required|string|max:11|unique:lines,phone_number',
            'contact_number' => 'nullable|string|max:11',
            'whatsapp_number' => 'nullable|string|max:11',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'birth_date' => 'nullable|date',
        ]);

        $customer = Customer::create([
            'full_name' => $request->full_name,
            'national_id' => $request->national_id,
            'email' => $request->email,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
            'whatsapp_number' => $request->whatsapp_number,
        ]);

        // إنشاء الخط المرتبط بالعميل
        $customer->lines()->create([
            'phone_number' => $request->phone_number,
            'provider' => $request->provider,
            'status' => $request->status,
            'plan_id' => $request->plan_id,
            'line_type' => $request->line_type,
            'payment_date' => $request->payment_date,
            'added_by' => auth()->id(),
        ]);

        return redirect()->route('customers.index')->with('success', 'تمت إضافة العميل بنجاح');
    }

    public function show(Customer $customer)
    {
        $user = auth()->user();
        $isDistributor = $user->role && $user->role->name === 'موزع';

        $customer->load(['lines' => function($q) use ($isDistributor, $user) {
            if ($isDistributor) {
                $q->where('distributor_id', $user->id);
            }
        }]);

        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $customer->load('lines');
        $plans = Plan::all();
        return view('admin.customers.edit', compact('customer', 'plans'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'national_id' => 'required|string|size:14|unique:customers,national_id,' . $customer->id,
            'contact_number' => 'nullable|string|max:11',
            'whatsapp_number' => 'nullable|string|max:11',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'birth_date' => 'nullable|date',
        ]);

        $customer->update([
            'full_name' => $request->full_name,
            'national_id' => $request->national_id,
            'email' => $request->email,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
            'whatsapp_number' => $request->whatsapp_number,
        ]);

        // تحديث أول خط (بافتراض خط واحد هنا - يمكنك تعديل هذا لاحقًا لدعم أكثر من خط)
        // if ($customer->lines()->exists()) {
        //     $line = $customer->lines()->first();
        //     $line->update([
        //         'phone_number' => $request->phone_number,
        //         'provider' => $request->provider,
        //         'status' => $request->status,
        //         'plan_id' => $request->plan_id,
        //         'line_type' => $request->line_type,
        //         'payment_date' => $request->payment_date,
        //     ]);
        // }

        return redirect()->route('customers.index')->with('success', 'تم التحديث بنجاح');
    }

    public function destroy(Customer $customer)
    {
        abort_unless(auth()->user()->hasPermission('delete customer'), 403);
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'تم حذف العميل بنجاح');
    }

    public function export(Request $request)
    {
        return Excel::download(new CustomersExport($request), 'customers.xlsx');
    }
    public function search(Request $request)
{
    $term = $request->q;

    $customers = Customer::where('national_id', 'like', "%$term%")
        ->select('id', 'full_name', 'national_id')
        ->limit(20)
        ->get();

    return response()->json($customers);
}
public function searchByNationalId(Request $request)
{
    $query = $request->get('q', '');
    return Customer::where(function ($q2) use ($query) {
            $q2->where('national_id', 'like', "%$query%")
               ->orWhere('full_name', 'like', "%$query%");
        })
        ->select('id', 'full_name', 'national_id')
        ->limit(20)
        ->get();
}
public function trashed()
{
    $customers = Customer::onlyTrashed()->with('lines')->paginate(20);
    return view('admin.customers.trashed', compact('customers'));
}

public function restore($id)
{
    $customer = Customer::onlyTrashed()->findOrFail($id);
    $customer->restore();

    return redirect()->route('customers.trashed')->with('success', '✅ تم استرجاع العميل بنجاح');
}

public function forceDelete($id)
{
    abort_unless(auth()->user()->hasPermission('delete customer'), 403);
    $customer = Customer::onlyTrashed()->findOrFail($id);
    $customer->forceDelete();

    return redirect()->route('customers.trashed')->with('success', '🗑️ تم حذف العميل نهائياً');
}
public function addLine(Customer $customer)
{
    $lines = Line::query()
        
        ->orderBy('phone_number')
        ->get();

    return view('admin.customers.add-line', compact(
        'customer',
        'lines'
    ));
}
public function storeLine(Request $request, Customer $customer)
{
    $request->validate([
        'line_id' => ['required', 'exists:lines,id'],
    ]);

    $line = Line::findOrFail($request->line_id);

    $line->update([
        'customer_id' => $customer->id,
        'attached_at' => now(),
    ]);

    return redirect()
        ->route('customers.show', $customer)
        ->with('success', __('messages.line_attached_successfully'));
}
}
