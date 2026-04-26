<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = Provider::all();
        return view('admin.providers.index', compact('providers'));
    }

    public function create()
    {
        return view('admin.providers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:providers,name',
            'invoice_day' => 'required|integer|min:1|max:31',
        ]);

        Provider::create($request->all());

        return redirect()->route('providers.index')->with('success', '✅ تم إضافة المزود بنجاح.');
    }

    public function edit(Provider $provider)
    {
        return view('admin.providers.edit', compact('provider'));
    }

    public function update(Request $request, Provider $provider)
    {
        $request->validate([
            'name' => 'required|unique:providers,name,' . $provider->id,
            'invoice_day' => 'required|integer|min:1|max:31',
        ]);

        $provider->update($request->all());

        return redirect()->route('providers.index')->with('success', '✅ تم تحديث بيانات المزود بنجاح.');
    }

    public function destroy(Provider $provider)
    {
        $provider->delete();
        return redirect()->route('providers.index')->with('success', '✅ تم حذف المزود بنجاح.');
    }
}
