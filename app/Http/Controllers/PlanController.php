<?php
namespace App\Http\Controllers;
use App\Models\Plan;
use Illuminate\Http\Request;
use App\Exports\PlansExport;
use Maatwebsite\Excel\Facades\Excel;

class PlanController extends Controller
{
   public function index(Request $request)
{
    $plans = Plan::filter($request)->paginate(10)->appends($request->query());

    return view('admin.plans.index', compact('plans'));
}

public function trashed()
{
    $plans = Plan::onlyTrashed()->paginate(10);
    return view('admin.plans.trashed', compact('plans'));
}
public function restore($id)
{
    $plan = Plan::onlyTrashed()->findOrFail($id);
    $plan->restore();

    return redirect()->route('plans.trashed')->with('success', 'تم استعادة النظام بنجاح');
}
public function forceDelete($id)
{
    abort_unless(auth()->user()->hasPermission('delete plan'), 403);
    $plan = Plan::onlyTrashed()->findOrFail($id);
    $plan->forceDelete();

    return redirect()->route('plans.trashed')->with('success', 'تم حذف النظام نهائيًا');
}

    public function export(Request $request)
    {
        return Excel::download(new PlansExport($request), 'plans.xlsx');
    }

    public function create()
{
    return view('admin.plans.create');
}

public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string',
        'price' => 'required|numeric',
        'provider' => 'nullable|string',
        'provider_price' => 'nullable|numeric',
        'type' => 'nullable|string',
        'identifier' => 'nullable|string',
        'penalty' => 'nullable|string',
        'plan_code' => 'nullable',
        
    ]);

    Plan::create($data);

    return redirect()->route('plans.index')->with('success', 'تم إنشاء النظام بنجاح');
}

public function edit(Plan $plan)
{
    return view('admin.plans.edit', compact('plan'));
}

public function update(Request $request, Plan $plan)
{
    $data = $request->validate([
        'name' => 'required|string',
        'price' => 'required|numeric',
        'provider' => 'nullable|string',
        'provider_price' => 'nullable|numeric',
        'type' => 'nullable|string',
        'identifier' => 'nullable|string',
        'penalty' => 'nullable|string',
        'plan_code' => 'nullable',
    ]);

    $plan->update($data);

    return redirect()->route('plans.index')->with('success', 'تم تعديل النظام بنجاح');
}

public function show(Plan $plan)
{
    return view('admin.plans.show', compact('plan'));
}
public function destroy(Plan $plan)
    {
        abort_unless(auth()->user()->hasPermission('delete plan'), 403);
        $plan->delete();

        return redirect()->route('plans.index')->with('success', 'تم حذف النظام بنجاح');
    }
}
