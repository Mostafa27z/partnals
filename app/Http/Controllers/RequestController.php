<?php

namespace App\Http\Controllers;

use App\Models\Request;
use Illuminate\Http\Request as HttpRequest;
use App\Models\Request as RequestModel;
use App\Models\User;
use App\Models\RequestResell;
use App\Models\RequestResumeLine;
use App\Models\Line;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use App\Models\RequestChangeDistributor;
use App\Models\Plan;
use App\Models\RequestChangePlan;
use App\Models\RequestChangeChip;
use App\Models\RequestPauseLine;
// use App\Models\LineRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RequestsExport;
use App\Models\RequestStopLine;
use App\Models\RequestChangeDate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ErrorExport implements FromCollection, WithHeadings
{
    protected $rows;
    protected $headings;

    public function __construct(array $rows, array $headings)
    {
        $this->rows = collect($rows);
        $this->headings = $headings;
    }

    public function collection()
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->headings;
    }
}

class RequestController extends Controller

{
    


public function importPauseRequests(HttpRequest $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx',
    ]);

    $rows = Excel::toCollection(null, $request->file('file'))->first();
    
    // 1. Template Validation
    $expectedHeaders = ['رقم الهاتف', 'السبب', 'ملاحظات'];
    $actualHeaders = isset($rows[0]) ? $rows[0]->toArray() : [];
    if (array_slice($actualHeaders, 0, 3) !== $expectedHeaders) {
        return redirect()->back()->withErrors(['file' => '❌ خطأ في تنسيق الملف: بعض الأعمدة مفقودة أو غير مرتبة بشكل صحيح. يرجى استخدام النموذج المعتمد.']);
    }

    $imported = 0;
    $errors = [];

    foreach ($rows as $index => $row) {
        if ($index === 0) continue;

        $phone = trim($row[0] ?? '');
        $reason = trim($row[1] ?? '');
        $comment = trim($row[2] ?? '');

        if (!$phone) continue;

        // 2. Query with Distributor Scoping
        $query = Line::where('phone_number', $phone);
        if (auth()->user()->role?->name === 'موزع') {
            $query->where('distributor_id', auth()->id());
        }
        $line = $query->first();

        if (!$line) {
            $errors[] = [$phone, $reason, $comment, "❌ رقم الهاتف غير موجود أو غير تابع لك"];
            continue;
        }

        if (RequestModel::hasActiveRequest($line->id, 'pause')) {
            $errors[] = [$phone, $reason, $comment, "❌ هناك طلب إيقاف مؤقت معلق بالفعل لهذا الرقم"];
            continue;
        }

        $mainRequest = RequestModel::create([
            'line_id'      => $line->id,
            'customer_id'  => $line->customer_id,
            'request_type' => 'pause',
            'status'       => 'pending',
            'requested_by' => auth()->id(),
        ]);

        RequestPauseLine::create([
            'request_id' => $mainRequest->id,
            'reason'     => $reason,
            'comment'    => $comment,
        ]);

        $imported++;
    }

    // 3. Row-by-Row Error Excel
    if (count($errors)) {
        $filename = 'pause_import_errors_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(
            new ErrorExport($errors, ['رقم الهاتف', 'السبب', 'ملاحظات', 'الخطأ']),
            $filename
        );
    }

    return redirect()->back()->with('success', "✅ تم استيراد $imported طلب بنجاح.");
}

public function importResumeRequests(HttpRequest $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx',
    ]);

    $rows = Excel::toCollection(null, $request->file('file'))->first();
    
    // 1. Template Validation
    $expectedHeaders = ['رقم الهاتف', 'السبب', 'ملاحظات'];
    $actualHeaders = isset($rows[0]) ? $rows[0]->toArray() : [];
    if (array_slice($actualHeaders, 0, 3) !== $expectedHeaders) {
        return redirect()->back()->withErrors(['file' => 'الجدول غير مطابق للنموذج المعتمد. يرجى تحميل النموذج الصحيح.']);
    }

    $imported = 0;
    $errors = [];

    foreach ($rows as $index => $row) {
        if ($index === 0) continue;

        $phone = trim($row[0] ?? '');
        $reason = trim($row[1] ?? '');
        $comment = trim($row[2] ?? '');

        if (!$phone) continue;

        // 2. Query with Distributor Scoping
        $query = Line::where('phone_number', $phone);
        if (auth()->user()->role?->name === 'موزع') {
            $query->where('distributor_id', auth()->id());
        }
        $line = $query->first();

        if (!$line) {
            $errors[] = [$phone, $reason, $comment, "❌ رقم الهاتف غير موجود أو غير تابع لك"];
            continue;
        }

        if (RequestModel::hasActiveRequest($line->id, 'resume')) {
            $errors[] = [$phone, $reason, $comment, "❌ هناك طلب تشغيل معلق بالفعل لهذا الرقم"];
            continue;
        }

        $mainRequest = RequestModel::create([
            'line_id'      => $line->id,
            'customer_id'  => $line->customer_id,
            'request_type' => 'resume',
            'status'       => 'pending',
            'requested_by' => auth()->id(),
        ]);

        RequestResumeLine::create([
            'request_id' => $mainRequest->id,
            'reason'     => $reason,
            'comment'    => $comment,
        ]);

        $imported++;
    }

    // 3. Row-by-Row Error Excel
    if (count($errors)) {
        $filename = 'resume_import_errors_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(
            new ErrorExport($errors, ['رقم الهاتف', 'السبب', 'ملاحظات', 'الخطأ']),
            $filename
        );
    }

    return redirect()->back()->with('success', "✅ تم استيراد $imported طلب بنجاح.");
}


public function importChangeDateRequests(HttpRequest $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx',
    ]);

    $rows = Excel::toCollection(null, $request->file('file'))->first();
    
    // 1. Template Validation
    $expectedHeaders = ['رقم الهاتف', 'التاريخ الجديد YYYY-MM-DD', 'السبب', 'ملاحظات'];
    $actualHeaders = isset($rows[0]) ? $rows[0]->toArray() : [];
    if (array_slice($actualHeaders, 0, 4) !== $expectedHeaders) {
        return redirect()->back()->withErrors(['file' => '❌ خطأ في تنسيق الملف: بعض الأعمدة مفقودة أو غير مرتبة بشكل صحيح. يرجى استخدام النموذج المعتمد.']);
    }

    $imported = 0;
    $errors = [];

    foreach ($rows as $index => $row) {
        if ($index === 0) continue;

        $phone = trim($row[0] ?? '');
        $newDate = trim($row[1] ?? '');
        $reason = trim($row[2] ?? '');
        $comment = trim($row[3] ?? '');

        if (!$phone) continue;

        // 2. Query with Distributor Scoping
        $query = Line::where('phone_number', $phone);
        if (auth()->user()->role?->name === 'موزع') {
            $query->where('distributor_id', auth()->id());
        }
        $line = $query->first();

        if (!$line) {
            $errors[] = [$phone, $newDate, $reason, $comment, "❌ رقم الهاتف غير موجود أو غير تابع لك"];
            continue;
        }

        if (RequestModel::hasActiveRequest($line->id, 'change_date')) {
            $errors[] = [$phone, $newDate, $reason, $comment, "❌ هناك طلب تغيير تاريخ معلق بالفعل لهذا الرقم"];
            continue;
        }

        if (!$newDate || !\Carbon\Carbon::canBeCreatedFromFormat($newDate, 'Y-m-d')) {
            $errors[] = [$phone, $newDate, $reason, $comment, "❌ التاريخ غير صالح (يجب أن يكون YYYY-MM-DD)"];
            continue;
        }

        $mainRequest = RequestModel::create([
            'line_id'      => $line->id,
            'customer_id'  => $line->customer_id,
            'request_type' => 'change_date',
            'status'       => 'pending',
            'requested_by' => auth()->id(),
        ]);

        RequestChangeDate::create([
            'request_id'   => $mainRequest->id,
            'current_date' => $line->last_invoice_date ?? now()->toDateString(), // Mandatory field
            'new_date'     => $newDate,
            'reason'       => "{$reason} | {$comment}",
        ]);

        $imported++;
    }

    if (count($errors)) {
        $filename = 'change_date_import_errors_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(
            new ErrorExport($errors, ['رقم الهاتف', 'التاريخ الجديد', 'السبب', 'ملاحظات', 'الخطأ']),
            $filename
        );
    }

    return redirect()->back()->with('success', "✅ تم استيراد $imported طلب بنجاح.");
}


public function importChangeDistributorRequests(HttpRequest $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx',
    ]);

    $rows = Excel::toCollection(null, $request->file('file'))->first();
    
    // 1. Template Validation
    $expectedHeaders = ['رقم الهاتف', 'اسم الموزع الجديد', 'السبب', 'ملاحظات'];
    $actualHeaders = isset($rows[0]) ? $rows[0]->toArray() : [];
    if (array_slice($actualHeaders, 0, 4) !== $expectedHeaders) {
        return redirect()->back()->withErrors(['file' => '❌ خطأ في تنسيق الملف: بعض الأعمدة مفقودة أو غير مرتبة بشكل صحيح. يرجى استخدام النموذج المعتمد.']);
    }

    $imported = 0;
    $errors = [];

    foreach ($rows as $index => $row) {
        if ($index === 0) continue;

        $phone = trim($row[0] ?? '');
        $newDistributor = trim($row[1] ?? '');
        $reason = trim($row[2] ?? '');
        $comment = trim($row[3] ?? '');

        if (!$phone) continue;

        // 2. Query with Distributor Scoping
        $query = Line::where('phone_number', $phone);
        if (auth()->user()->role?->name === 'موزع') {
            $query->where('distributor_id', auth()->id());
        }
        $line = $query->first();

        if (!$line) {
            $errors[] = [$phone, $newDistributor, $reason, $comment, "❌ رقم الهاتف غير موجود أو غير تابع لك"];
            continue;
        }

        if (RequestModel::hasActiveRequest($line->id, 'change_distributor')) {
            $errors[] = [$phone, $newDistributor, $reason, $comment, "❌ هناك طلب تغيير موزع معلق بالفعل لهذا الرقم"];
            continue;
        }

        if (!$newDistributor) {
            $errors[] = [$phone, $newDistributor, $reason, $comment, "❌ اسم الموزع الجديد مفقود"];
            continue;
        }

        // Validate the new distributor exists in the system
        $exists = User::where('name', $newDistributor)->whereHas('role', function($q){ $q->where('name', 'موزع'); })->exists();
        if (!$exists) {
            $errors[] = [$phone, $newDistributor, $reason, $comment, "❌ الموزع المذكور غير موجود بنظامنا"];
            continue;
        }

        $mainRequest = RequestModel::create([
            'line_id'      => $line->id,
            'customer_id'  => $line->customer_id,
            'request_type' => 'change_distributor',
            'status'       => 'pending',
            'requested_by' => auth()->id(),
        ]);

        RequestChangeDistributor::create([
            'request_id'       => $mainRequest->id,
            'old_distributor'  => $line->distributor?->name,
            'new_distributor'  => $newDistributor,
            'reason'           => "{$reason} | {$comment}",
        ]);

        $imported++;
    }

    if (count($errors)) {
        $filename = 'change_distributor_errors_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(
            new ErrorExport($errors, ['رقم الهاتف', 'الموزع الجديد', 'السبب', 'ملاحظات', 'الخطأ']),
            $filename
        );
    }

    return redirect()->back()->with('success', "✅ تم استيراد $imported طلب بنجاح.");
}


public function importChangeChipRequests(HttpRequest $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx',
    ]);

    $rows = Excel::toCollection(null, $request->file('file'))->first();
    
    // 1. Template Validation
    $expectedHeaders = ['رقم الهاتف', 'السيريال الجديد', 'السبب', 'ملاحظات'];
    $actualHeaders = isset($rows[0]) ? $rows[0]->toArray() : [];
    if (array_slice($actualHeaders, 0, 4) !== $expectedHeaders) {
        return redirect()->back()->withErrors(['file' => '❌ خطأ في تنسيق الملف: بعض الأعمدة مفقودة أو غير مرتبة بشكل صحيح. يرجى استخدام النموذج المعتمد.']);
    }

    $imported = 0;
    $errors = [];

    foreach ($rows as $index => $row) {
        if ($index === 0) continue;

        $phone = trim($row[0] ?? '');
        $newSerial = trim($row[1] ?? '');
        $reason = trim($row[2] ?? '');
        $comment = trim($row[3] ?? '');

        if (!$phone) continue;

        // 2. Query with Distributor Scoping
        $query = Line::where('phone_number', $phone);
        if (auth()->user()->role?->name === 'موزع') {
            $query->where('distributor_id', auth()->id());
        }
        $line = $query->first();

        if (!$line) {
            $errors[] = [$phone, $newSerial, $reason, $comment, "❌ رقم الهاتف غير موجود أو غير تابع لك"];
            continue;
        }

        if (RequestModel::hasActiveRequest($line->id, 'change_chip')) {
            $errors[] = [$phone, $newSerial, $reason, $comment, "❌ هناك طلب تغيير شريحة معلق بالفعل لهذا الرقم"];
            continue;
        }

        if (!$newSerial) {
            $errors[] = [$phone, $newSerial, $reason, $comment, "❌ رقم الشريحة الجديدة مفقود"];
            continue;
        }

        $mainRequest = RequestModel::create([
            'line_id' => $line->id,
            'customer_id' => $line->customer_id,
            'request_type' => 'change_chip',
            'status' => 'pending',
            'requested_by' => auth()->id(),
        ]);

        RequestChangeChip::create([
            'request_id'   => $mainRequest->id,
            'change_type'  => 'chip', // Default type for bulk imports
            'new_serial'   => $newSerial,
            'old_serial'   => $line->serial_number, // Pass current serial
            'request_date' => now(), // Mandatory field
            'comment'      => "{$reason} | {$comment}",
        ]);

        $imported++;
    }

    if (count($errors)) {
        $filename = 'change_chip_import_errors_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(
            new ErrorExport($errors, ['رقم الهاتف', 'السيريال الجديد', 'السبب', 'ملاحظات', 'الخطأ']),
            $filename
        );
    }

    return redirect()->back()->with('success', "✅ تم استيراد $imported طلب بنجاح.");
}



public function importChangePlanRequests(HttpRequest $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx',
    ]);

    $rows = Excel::toCollection(null, $request->file('file'))->first();
    
    // 1. Template Validation
    $expectedHeaders = ['رقم الهاتف', 'اسم النظام الجديد', 'السبب', 'ملاحظات'];
    $actualHeaders = isset($rows[0]) ? $rows[0]->toArray() : [];
    if (array_slice($actualHeaders, 0, 4) !== $expectedHeaders) {
        return redirect()->back()->withErrors(['file' => '❌ خطأ في تنسيق الملف: بعض الأعمدة مفقودة أو غير مرتبة بشكل صحيح. يرجى استخدام النموذج المعتمد.']);
    }

    $imported = 0;
    $errors = [];

    foreach ($rows as $index => $row) {
        if ($index === 0) continue;

        $phone = trim($row[0] ?? '');
        $newPlanName = trim($row[1] ?? '');
        $reason = trim($row[2] ?? '');
        $comment = trim($row[3] ?? '');

        if (!$phone) continue;

        // 2. Query with Distributor Scoping
        $query = Line::where('phone_number', $phone);
        if (auth()->user()->role?->name === 'موزع') {
            $query->where('distributor_id', auth()->id());
        }
        $line = $query->first();

        if (!$line) {
            $errors[] = [$phone, $newPlanName, $reason, $comment, "❌ رقم الهاتف غير موجود أو غير تابع لك"];
            continue;
        }

        if (RequestModel::hasActiveRequest($line->id, 'change_plan')) {
            $errors[] = [$phone, $newPlanName, $reason, $comment, "❌ هناك طلب تغيير نظام معلق بالفعل لهذا الرقم"];
            continue;
        }

        $newPlan = Plan::where('name', $newPlanName)->first();
        if (!$newPlan) {
            $errors[] = [$phone, $newPlanName, $reason, $comment, "❌ النظام المذكور ($newPlanName) غير موجود"];
            continue;
        }

        $mainRequest = RequestModel::create([
            'line_id' => $line->id,
            'customer_id' => $line->customer_id,
            'request_type' => 'change_plan',
            'status' => 'pending',
            'requested_by' => auth()->id(),
        ]);

        RequestChangePlan::create([
            'request_id'    => $mainRequest->id,
            'old_plan_name' => $line->plan?->name ?? 'بدون نظام',
            'new_plan_id'   => $newPlan->id,
            'reason'        => $reason,
            'comment'       => $comment,
        ]);

        $imported++;
    }

    if (count($errors)) {
        $filename = 'change_plan_import_errors_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(
            new ErrorExport($errors, ['رقم الهاتف', 'النظام الجديد', 'السبب', 'ملاحظات', 'الخطأ']),
            $filename
        );
    }

    return redirect()->back()->with('success', "✅ تم استيراد $imported طلب بنجاح.");
}



public function importStopRequests(HttpRequest $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx'
    ]);

    $rows = Excel::toCollection(null, $request->file('file'))->first();
    
    // 1. Template Validation
    $expectedHeaders = ['رقم الهاتف', 'السبب', 'ملاحظات'];
    $actualHeaders = isset($rows[0]) ? $rows[0]->toArray() : [];
    if (array_slice($actualHeaders, 0, 3) !== $expectedHeaders) {
        return redirect()->back()->withErrors(['file' => '❌ خطأ في تنسيق الملف: بعض الأعمدة مفقودة أو غير مرتبة بشكل صحيح. يرجى استخدام النموذج المعتمد.']);
    }

    $imported = 0;
    $errors = [];

    foreach ($rows as $index => $row) {
        if ($index === 0) continue;

        $phone = trim($row[0] ?? '');
        $reason = trim($row[1] ?? '');
        $comment = trim($row[2] ?? '');

        if (!$phone) continue;

        // 2. Query with Distributor Scoping
        $query = Line::where('phone_number', $phone);
        if (auth()->user()->role?->name === 'موزع') {
            $query->where('distributor_id', auth()->id());
        }
        $line = $query->first();

        if (!$line) {
            $errors[] = [$phone, $reason, $comment, "❌ رقم الهاتف غير موجود أو غير تابع لك"];
            continue;
        }

        if (RequestModel::hasActiveRequest($line->id, 'stop')) {
            $errors[] = [$phone, $reason, $comment, "❌ هناك طلب إيقاف معلق بالفعل لهذا الرقم"];
            continue;
        }

        $mainRequest = RequestModel::create([
            'line_id'      => $line->id,
            'customer_id'  => $line->customer_id,
            'request_type' => 'stop',
            'status'       => 'pending',
            'requested_by' => auth()->id(),
        ]);

        RequestStopLine::create([
            'request_id' => $mainRequest->id,
            'reason'     => $reason,
            'comment'    => $comment,
        ]);

        $imported++;
    }

    if (count($errors)) {
        $filename = 'stop_import_errors_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(
            new ErrorExport($errors, ['رقم الهاتف', 'السبب', 'ملاحظات', 'الخطأ']),
            $filename
        );
    }

    return redirect()->back()->with('success', "✅ تم استيراد $imported طلب بنجاح.");
}




public function importResellRequests(HttpRequest $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx',
    ]);

    $rows = Excel::toCollection(null, $request->file('file'))->first();
    
    // 1. Template Validation
    $expectedHeaders = ['رقم الهاتف', 'النوع', 'السيريال القديم', 'السيريال الجديد', 'ملاحظات'];
    $actualHeaders = isset($rows[0]) ? $rows[0]->toArray() : [];
    if (array_slice($actualHeaders, 0, 5) !== $expectedHeaders) {
        return redirect()->back()->withErrors(['file' => '❌ خطأ في تنسيق الملف: بعض الأعمدة مفقودة أو غير مرتبة بشكل صحيح. يرجى استخدام النموذج المعتمد.']);
    }

    $imported = 0;
    $errors = [];

    foreach ($rows as $index => $row) {
        if ($index === 0) continue;

        $phone = trim($row[0] ?? '');
        $type = trim($row[1] ?? ''); // chip or branch
        $oldSerial = trim($row[2] ?? '');
        $newSerial = trim($row[3] ?? '');
        $comment = trim($row[4] ?? '');

        if (!$phone) continue;

        // 2. Query with Distributor Scoping
        $query = Line::where('phone_number', $phone);
        if (auth()->user()->role?->name === 'موزع') {
            $query->where('distributor_id', auth()->id());
        }
        $line = $query->first();

        if (!$line) {
            $errors[] = [$phone, $type, $oldSerial, $newSerial, $comment, "❌ رقم الهاتف غير موجود أو غير تابع لك"];
            continue;
        }

        if (RequestModel::hasActiveRequest($line->id, 'resell')) {
            $errors[] = [$phone, $type, $oldSerial, $newSerial, $comment, "❌ هناك طلب إعادة بيع معلق بالفعل لهذا الرقم"];
            continue;
        }

        if (!in_array($type, ['chip', 'branch'])) {
            $errors[] = [$phone, $type, $oldSerial, $newSerial, $comment, "❌ نوع غير صحيح (يجب أن يكون chip أو branch)"];
            continue;
        }

        $mainRequest = RequestModel::create([
            'line_id' => $line->id,
            'customer_id' => $line->customer_id,
            'request_type' => 'resell',
            'status' => 'pending',
            'requested_by' => auth()->id(),
        ]);

        RequestResell::create([
            'request_id'   => $mainRequest->id,
            'resell_type'  => $type, // already checked to be chip or branch
            'old_serial'   => $line->serial_number,
            'new_serial'   => $newSerial,
            'request_date' => now(),
            'comment'      => $comment,
        ]);

        $imported++;
    }

    if (count($errors)) {
        $filename = 'resell_import_errors_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(
            new ErrorExport($errors, ['رقم الهاتف', 'النوع', 'السيريال القديم', 'السيريال الجديد', 'ملاحظات', 'الخطأ']),
            $filename
        );
    }

    return redirect()->back()->with('success', "✅ تم استيراد $imported طلب بنجاح.");
}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function stopLineRequests(HttpRequest $request)
    {
    $user = auth()->user();
    $isDistributor = $user->role && $user->role->name === 'موزع';

    $query = RequestModel::where('request_type', 'stop')
        ->with(['line.customer', 'stopDetails', 'requestedBy', 'doneBy']);

    if ($isDistributor) {
        $query->whereHas('line', fn($q) => $q->where('distributor_id', $user->id));
    }

    // فلترة حسب الرقم القومي
    if ($request->filled('nid')) {
        $query->whereHas('line.customer', fn($q) => $q->where('national_id', 'like', '%' . $request->nid . '%'));
    }

    // فلترة حسب رقم الهاتف
    if ($request->filled('phone')) {
        $query->whereHas('line', fn($q) => $q->where('phone_number', 'like', '%' . $request->phone . '%'));
    }

    // فلترة حسب مزود الخدمة
    if ($request->filled('provider')) {
        $query->whereHas('line', fn($q) => $q->where('provider', $request->provider));
    }

    // فلترة حسب الموزع
    if ($request->filled('distributor')) {
        $query->whereHas('line', fn($q) => $q->where('distributor', 'like', '%' . $request->distributor . '%'));
    }

    // فلترة حسب من أنشأ الطلب
    if ($request->filled('requested_by')) {
        $query->where('requested_by', $request->requested_by);
    }

    // فلترة حسب من نفذ الطلب
    if ($request->filled('done_by')) {
        $query->where('done_by', $request->done_by);
    }

    // فلترة حسب تاريخ آخر فاتورة
    if ($request->filled('from')) {
        $query->whereHas('stopDetails', fn($q) => $q->whereDate('last_invoice_date', '>=', $request->from));
    }

    if ($request->filled('to')) {
        $query->whereHas('stopDetails', fn($q) => $q->whereDate('last_invoice_date', '<=', $request->to));
    }

    $requests = $query->latest()->paginate(20);
    $users = User::select('id', 'name')->get();

    return view('admin.requests.stop-lines', compact('requests', 'users'));
}
public function createStop(Line $line)
{
    return view('admin.requests.stop-create', compact('line'));
}
public function storeStop(HttpRequest $request)
{
    $request->validate([
        'line_id'     => 'required|exists:lines,id',
        'customer_id' => 'required|exists:customers,id',
        'reason'      => 'required|string|max:255',
        'comment'     => 'nullable|string|max:1000',
    ]);

    if (\App\Models\Request::hasActiveRequest($request->line_id, 'stop')) {
        return back()->withInput()->withErrors(['line_id' => '❌ هناك طلب إيقاف معلق بالفعل لهذا الرقم.']);
    }

    $requestRecord = \App\Models\Request::create([
        'line_id'      => $request->line_id,
        'customer_id'  => $request->customer_id,
        'request_type' => 'stop',
        'status'       => 'pending',
        'requested_by' => auth()->id(),
    ]);

    \App\Models\RequestStopLine::create([
        'request_id' => $requestRecord->id,
        'reason'     => $request->reason,
        'comment'    => $request->comment,
    ]);

    return redirect()->route('requests.all')->with('success', '✅ تم إنشاء طلب الإيقاف النهائي بنجاح.');
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HttpRequest $request)
    {
        //
    }
public function createChangeDate(Line $line)
{
    return view('admin.requests.create-change-date', compact('line'));
}

public function storeChangeDate(HttpRequest $request)
{
    $validated = $request->validate([
        'line_id'      => 'required|exists:lines,id',
        'new_date'     => 'required|date|after:1900-01-01',
        'reason'       => 'nullable|string|max:255',
    ]);

    if (\App\Models\Request::hasActiveRequest($validated['line_id'], 'change_date')) {
        return back()->withInput()->withErrors(['line_id' => '❌ هناك طلب تغيير تاريخ معلق بالفعل لهذا الرقم.']);
    }

    $line = Line::findOrFail($validated['line_id']);

    // إنشاء الطلب الأساسي
    $requestModel = \App\Models\Request::create([
        'line_id'      => $line->id,
        'customer_id'  => $line->customer_id,
        'request_type' => 'change_date',
        'status'       => 'pending',
        'requested_by' => auth()->id(),
    ]);

    // حفظ التفاصيل
    \App\Models\RequestChangeDate::create([
        'request_id'   => $requestModel->id,
        'current_date' => $line->last_invoice_date,
        'new_date'     => $validated['new_date'],
        'reason'       => $validated['reason'],
    ]);

    return redirect()->route('requests.all')->with('success', '✅ تم عمل الطلب بنجاح.');
}

    /**
     * Display the specified resource.
     */
    // public function show(HttpRequest $request)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HttpRequest $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HttpRequest $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HttpRequest $request)
    {
        //
    }
    

private function applyRequestEffects(RequestModel $request)
{
    match ($request->request_type) {
        'change_plan' => $this->applyChangePlan($request),
        'change_chip' => $this->applyChangeChip($request),
        'change_distributor' => $this->applyChangeDistributor($request),
        'stop' => $this->applyStopLine($request),
        'pause' => $this->applyPauseLine($request),
        'resume' => $this->applyResumeLine($request),
        'resell' => $this->applyResell($request),
        'change_date' => $this->applyChangeDate($request),
        default => null
    };
}

private function handleTargetRelease(RequestModel $request)
{
    if ($request->requested_by) {
        $user = User::find($request->requested_by);
        if ($user) {
            $user->checkAndReleaseTargets($request->updated_at->month, $request->updated_at->year);
        }
    }
}

public function updateStatus(HttpRequest $httpRequest, RequestModel $request) 
{
    $newStatus = $httpRequest->status;
    $oldStatus = $httpRequest->old_status;

    if ($request->status !== $oldStatus) {
        return back()->withErrors([
            'status' => "❌ الحالة الحالية هي {$request->status}، ولا تتطابق مع الحالة السابقة المدخلة ({$oldStatus})"
        ]);
    }

    // ✅ لو الحالة الجديدة "done" نفذ التأثير الخاص بالطلب
    if ($newStatus === 'done' && $request->status !== 'done') {
        $this->applyRequestEffects($request);
    }

    $request->update([
        'status' => $newStatus,
        'done_by' => auth()->id(),
    ]);

    // إذا تم تحديث الحالة إلى "done"، نتحقق من مكافآت المستهدفات للموظف الذي أنشأ الطلب
    if ($newStatus === 'done') {
        $this->handleTargetRelease($request);
    }

    return back()->with('success', '✅ تم تحديث حالة الطلب بنجاح.');
}
protected function applyChangePlan(RequestModel $request)
{
    $data = RequestChangePlan::where('request_id', $request->id)->first();
    if ($data && $data->new_plan_id) {
        $request->line()->update([
            'plan_id' => $data->new_plan_id,
        ]);
    }
}

protected function applyStopLine(RequestModel $request) 
{
    $data = RequestStopLine::where('request_id', $request->id)->first(); 

    if ($data) {
        $request->line->update([
            'status' => 'inactive',
        ]);
    }
}

protected function applyPauseLine(RequestModel $request)
{
    $data = RequestPauseLine::where('request_id', $request->id)->first();
    if ($data) {
        $request->line->update([
            'status' => 'inactive',
        ]);
    }
}
protected function applyResumeLine(RequestModel $request)
{
    $data = RequestResumeLine::where('request_id', $request->id)->first();
    if ($data) {
        $request->line->update([
            'status' => 'active',
        ]);
    }
}
protected function applyResell(RequestModel $request)
{
    $data = RequestResell::where('request_id', $request->id)->first();
    if (!$data) return;

    // إن وجد الرقم القومي، حاول ربطه بعميل
    $customer = null;
    if ($data->national_id) {
        $customer = Customer::where('national_id', $data->national_id)->first();
        if (!$customer && $data->full_name) {
            $customer = Customer::create([
                'full_name'   => $data->full_name,
                'national_id' => $data->national_id,
            ]);
        }
    }

    $updateData = [
        'customer_id' => $customer?->id,
        'attached_at' => now(),
    ];

    if ($data->new_serial) {
        $updateData['serial_number'] = $data->new_serial;
    }

    // تحديث الخط
    $request->line->update($updateData);

    if ($customer && request('transfer_invoices') == '1') {
        \App\Models\Invoice::where('line_id', $request->line->id)->update(['customer_id' => $customer->id]);
    }

    // ✅ أتمتة حساب الأرباح: حفظ سعر الشراء الحالي في سجل الطلب
    $data->update([
        'buy_price' => (float)$request->line->buy_price,
        'is_sold'   => true,
    ]);
}
protected function applyChangeDate(RequestModel $request)
{
    if ($request->changeDate && $request->changeDate->new_date) {
        $request->line->update([
            'last_invoice_date' => $request->changeDate->new_date,
        ]);
    }
}
protected function applyChangeChip(RequestModel $request)
{
    $data = RequestChangeChip::where('request_id', $request->id)->first();
    if (!$data) return;

    $updateData = [];

    if ($data->new_serial) {
        $updateData['serial_number'] = $data->new_serial;
    }

    if ($data->national_id) {
        $customer = Customer::where('national_id', $data->national_id)->first();
        if (!$customer && $data->full_name) {
            $customer = Customer::create([
                'full_name'   => $data->full_name,
                'national_id' => $data->national_id,
            ]);
        }
        if ($customer) {
            $updateData['customer_id'] = $customer->id;
            $request->update(['customer_id' => $customer->id]);
        }
    }

    if (!empty($updateData)) {
        $request->line->update($updateData);
    }
}



public function createResell(Line $line)
{
    $line->load('customer');
    return view('admin.requests.create-resell', compact('line'));
}







public function storeResell(HttpRequest $request)
{
    $validated = $request->validate([
    'line_id'      => 'required|exists:lines,id',
    'resell_type'  => 'required|in:chip,branch',
    'old_serial'   => 'nullable|regex:/^\d+$/|size:19',
    'new_serial'   => 'required_if:resell_type,chip|regex:/^\d+$/|size:19',
    'request_date' => 'required|date',
    'comment'      => 'nullable|string|max:1000',
    'full_name'    => 'nullable|required_if:resell_type,branch|string|max:255',
    'national_id'  => 'nullable|required_if:resell_type,branch|digits:14',
    'sale_price'   => 'nullable|numeric|min:0',
], [
    'resell_type.required'     => 'يجب اختيار نوع إعادة البيع.',
    'new_serial.required_if'   => 'يجب إدخال المسلسل الجديد عند اختيار نوع الشريحة.',
    'new_serial.regex'         => 'المسلسل الجديد يجب أن يحتوي على أرقام فقط.',
    'old_serial.regex'         => 'المسلسل القديم يجب أن يحتوي على أرقام فقط.',
    'full_name.required_if'    => 'يجب إدخال الاسم عند اختيار النوع فرع.',
    'national_id.required_if'  => 'يجب إدخال الرقم القومي عند اختيار النوع فرع.',
]);

    if (\App\Models\Request::hasActiveRequest($validated['line_id'], 'resell')) {
        return back()->withInput()->withErrors(['line_id' => '❌ هناك طلب إعادة بيع معلق بالفعل لهذا الرقم.']);
    }

    $line = \App\Models\Line::find($validated['line_id']);

    // 🧩 إنشاء الطلب الأساسي
    $requestRecord = RequestModel::create([
        'line_id'      => $validated['line_id'],
        'customer_id'  => $line->customer_id,
        'request_type' => 'resell',
        'status'       => 'pending',
        'requested_by' => Auth::id(),
    ]);

    // 🧩 حفظ تفاصيل إعادة البيع
    RequestResell::create([
        'request_id'   => $requestRecord->id,
        'resell_type'  => $validated['resell_type'],
        'old_serial'   => $validated['old_serial'],
        'new_serial'   => $validated['new_serial'] ?? null,
        'request_date' => $validated['request_date'],
        'full_name'    => $validated['full_name'] ?? null,
        'national_id'  => $validated['national_id'] ?? null,
        'comment'      => $validated['comment'],
        'buy_price'    => $line->buy_price,
        'sale_price'   => $request->sale_price,
    ]);

    // Update line sale price if provided
    if ($request->filled('sale_price')) {
        $line->update(['sale_price' => $request->sale_price]);
    }

    return redirect()->route('requests.all')->with('success', '✅ تم إنشاء طلب إعادة البيع بنجاح');
}

public function resellRequests(HttpRequest $request)
{
    $user = auth()->user();
    $isDistributor = $user->role && $user->role->name === 'موزع';

    $query = Request::where('request_type', 'resell')
        ->with(['line.customer', 'resellDetails', 'requestedBy', 'doneBy']);

    if ($isDistributor) {
        $query->whereHas('line', fn($q) => $q->where('distributor_id', $user->id));
    }

    if ($request->filled('resell_type')) {
        $query->whereHas('resellDetails', fn($q) =>
            $q->where('resell_type', $request->resell_type));
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('from')) {
        $query->whereHas('resellDetails', fn($q) =>
            $q->whereDate('request_date', '>=', $request->from));
    }

    if ($request->filled('to')) {
        $query->whereHas('resellDetails', fn($q) =>
            $q->whereDate('request_date', '<=', $request->to));
    }

    if ($request->filled('name')) {
        $query->whereHas('line.customer', fn($q) =>
            $q->where('full_name', 'like', '%' . $request->name . '%'));
    }

    if ($request->filled('nid')) {
        $query->whereHas('line.customer', fn($q) =>
            $q->where('national_id', 'like', '%' . $request->nid . '%'));
    }

    if ($request->filled('provider')) {
        $query->whereHas('line', fn($q) =>
            $q->where('provider', 'like', '%' . $request->provider . '%'));
    }

    $requests = $query->latest()->paginate(20);

    return view('admin.requests.resell-index', compact('requests'));
}
public function resellDetails(RequestModel $request)
{
    $request->load(['line.customer', 'resellDetails', 'requestedBy', 'doneBy']);

    if (auth()->user()->role?->name === 'موزع' && $request->line?->distributor_id !== auth()->id()) {
        abort(403, 'غير مصرح لك بمشاهدة هذا الطلب.');
    }

    return view('admin.requests.resell-show', ['requestModel' => $request]);
}

public function completeResellSale(HttpRequest $httpRequest, RequestModel $request)
{
    if ($request->request_type !== 'resell') {
        return back()->with('error', '❌ هذا الطلب ليس طلب إعادة بيع.');
    }

    $line = $request->line;
    if (!$line) {
        return back()->with('error', '❌ الخط المرتبط بهذا الطلب غير موجود.');
    }

    $salePrice = (float)($request->resellDetails->sale_price ?? 0);

    // تحديث بيانات الطلب (سنا بشوت للأسعار) لضمان عدم تأثرها بتعديل الخط مستقبلاً
    if ($request->resellDetails) {
        $request->resellDetails->update([
            'buy_price'  => (float)$line->buy_price, // حفظ سعر الشراء الحالي كنسخة غير قابلة للتغيير
            'is_sold'    => true,
        ]);
    }

    // تحديث بيانات الخط وتعيينه كمباع
    $line->update([
        'sale_price' => $salePrice,
        'is_sold'    => true,
    ]);
    
    // تحديث التاريخ لضمان ظهوره في محاسبة الشهر الحالي
    $line->touch();

    return back()->with('success', '✅ تم إتمام البيعة وتسجيل الإيراد بنجاح.');
}


public function chooseLineForResell()
{
    $user = auth()->user();
    $query = \App\Models\Line::with('customer');

    if ($user->role?->name === 'موزع') {
        $query->where('distributor_id', $user->id);
    }

    $lines = $query->latest()->paginate(20);
    return view('admin.requests.choose-line-resell', compact('lines'));
}
private function providerCodeMap()
{
    return [
        'Vodafone' => '010',
        'Etisalat' => '011',
        'WE'       => '012',
        'Orange'   => '015',
    ];
}

public function createChangePlan(Line $line) 
{
    $plans = Plan::where('provider', $line->provider)->get();

    return view('admin.requests.create-change-plan', compact('line', 'plans'));
}


public function storeChangePlan(HttpRequest $request)
{
    $validated = $request->validate([
        'line_id' => 'required|exists:lines,id',
        'new_plan_id' => 'required|exists:plans,id',
        'comment' => 'nullable|string|max:1000',
    ]);

    if (\App\Models\Request::hasActiveRequest($validated['line_id'], 'change_plan')) {
        return back()->withInput()->withErrors(['line_id' => '❌ هناك طلب تغيير نظام معلق بالفعل لهذا الرقم.']);
    }

    $line = Line::findOrFail($validated['line_id']);

    $mainRequest = RequestModel::create([
        'line_id' => $line->id,
        'customer_id' => $line->customer_id,
        'request_type' => 'change_plan',
        'status' => 'pending',
        'requested_by' => auth()->id(),
    ]);

    RequestChangePlan::create([
        'request_id' => $mainRequest->id,
        'old_plan_name' => $line->plan?->name ?? 'بدون نظام',
        'new_plan_id' => $validated['new_plan_id'],
        'comment' => $validated['comment'] ?? null,
    ]);

    return redirect()->route('requests.all')->with('success', '✅ تم عمل الطلب بنجاح.');
}
// In RequestController.php



public function createChangeChip(Line $line)
{
    return view('admin.requests.create-change-chip', compact('line'));
}

    public function storeChangeChip(HttpRequest $request)
    {
        $validated = $request->validate([
        'line_id'      => 'required|exists:lines,id',
        'change_type'  => 'required|in:chip,branch',
        'old_serial'   => 'nullable|regex:/^\d+$/|size:19',
        'new_serial'   => 'required_if:change_type,chip|regex:/^\d+$/|size:19',
        'request_date' => 'required|date',
        'comment'      => 'nullable|string|max:1000',
        'full_name'    => 'required|string|max:255',
        'national_id'  => 'required|digits:14',
    ], [
        'change_type.required'     => 'يجب اختيار نوع  التغيير.',
        'new_serial.required_if'   => 'يجب إدخال المسلسل الجديد عند اختيار نوع الشريحة.',
        'new_serial.regex'         => 'المسلسل الجديد يجب أن يحتوي على أرقام فقط.',
        'old_serial.regex'         => 'المسلسل القديم يجب أن يحتوي على أرقام فقط.',
        'full_name.required'       => 'يجب إدخال الاسم بالكامل.',
        'national_id.required'     => 'يجب إدخال الرقم القومي.',
        'national_id.digits'       => 'الرقم القومي يجب أن يتكون من 14 رقماً.',
    ]);

    if (\App\Models\Request::hasActiveRequest($validated['line_id'], 'change_chip')) {
        return back()->withInput()->withErrors(['line_id' => '❌ هناك طلب تغيير شريحة معلق بالفعل لهذا الرقم.']);
    }

    $line = \App\Models\Line::findOrFail($validated['line_id']);

    $requestModel = \App\Models\Request::create([
        'line_id'      => $line->id,
        'customer_id'  => $line->customer_id,
        'request_type' => 'change_chip',
        'status'       => 'pending',
        'requested_by' => auth()->id(),
    ]);

    \App\Models\RequestChangeChip::create([
        'request_id'   => $requestModel->id,
        'change_type'  => $validated['change_type'],
        'old_serial'   => $validated['old_serial'] ?? null,
        'new_serial'   => $validated['new_serial'] ?? null,
        'full_name'    => $validated['full_name'] ?? null,
        'national_id'  => $validated['national_id'] ?? null,
        'request_date' => $validated['request_date'],
        'comment'      => $validated['comment'] ?? null,
    ]);

    return redirect()->route('requests.all')->with('success', '✅ تم إنشاء طلب تغيير الشريحة بنجاح');
}
public function createPause($lineId)
{
    $line = Line::with('customer')->findOrFail($lineId);
    return view('admin.requests.create-pause-request', compact('line'));
}
public function storePause(HttpRequest $request)
{
    $validated = $request->validate([
        'line_id'     => 'required|exists:lines,id',
        'reason'      => 'required|string|max:255',
        'comment'     => 'nullable|string|max:1000',
    ]);

    if (\App\Models\Request::hasActiveRequest($validated['line_id'], 'pause')) {
        return back()->withInput()->withErrors(['line_id' => '❌ هناك طلب إيقاف مؤقت معلق بالفعل لهذا الرقم.']);
    }

    $line = Line::findOrFail($validated['line_id']);

    $requestModel = RequestModel::create([
        'line_id'      => $line->id,
        'customer_id'  => $line->customer_id,
        'request_type' => 'pause',
        'status'       => 'pending',
        'requested_by' => auth()->id(),
    ]);

    RequestPauseLine::create([
        'request_id' => $requestModel->id,
        'reason'     => $validated['reason'],
        'comment'    => $validated['comment'],
    ]);

    return redirect()->route('requests.all')->with('success', '✅ تم عمل الطلب بنجاح.');
}
public function createResume($lineId)
{
    $line = Line::with('customer')->findOrFail($lineId);
    return view('admin.requests.create-resume', compact('line'));
}

public function storeResume(HttpRequest $request)
{
    $validated = $request->validate([
        'line_id' => 'required|exists:lines,id',
        'reason' => 'required|string|max:255',
        'comment' => 'nullable|string|max:1000',
    ]);

    if (\App\Models\Request::hasActiveRequest($validated['line_id'], 'resume')) {
        return back()->withInput()->withErrors(['line_id' => '❌ هناك طلب تشغيل معلق بالفعل لهذا الرقم.']);
    }

    $line = Line::findOrFail($validated['line_id']);

    $req = RequestModel::create([
        'line_id' => $line->id,
        'customer_id' => $line->customer_id,
        'request_type' => 'resume',
        'status' => 'pending',
        'requested_by' => auth()->id(),
    ]);

    RequestResumeLine::create([
        'request_id' => $req->id,
        'reason' => $validated['reason'],
        'comment' => $validated['comment'],
    ]);

     return redirect()->route('requests.all')->with('success', '✅ تم عمل الطلب بنجاح.');
}



public function createChangeDistributor(Line $line)
{
    $distributors = \App\Models\User::whereHas('roles', function ($query) {
        $query->where('name', 'موزع');
    })->get();
    
    return view('admin.requests.create-change-distributor', compact('line', 'distributors'));
}

public function storeChangeDistributor(HttpRequest $request)
{
    $validated = $request->validate([
        'line_id'            => 'required|exists:lines,id',
        'new_distributor_id' => 'required|exists:users,id',
        'reason'             => 'nullable|string|max:1000',
    ]);

    if (\App\Models\Request::hasActiveRequest($validated['line_id'], 'change_distributor')) {
        return back()->withInput()->withErrors(['line_id' => '❌ هناك طلب تغيير موزع معلق بالفعل لهذا الرقم.']);
    }

    $line = Line::findOrFail($validated['line_id']);

    $requestModel = RequestModel::create([
        'line_id'      => $line->id,
        'customer_id'  => $line->customer_id,
        'request_type' => 'change_distributor',
        'status'       => 'pending',
        'requested_by' => auth()->id(),
    ]);

    RequestChangeDistributor::create([
        'request_id'         => $requestModel->id,
        'old_distributor_id' => $line->distributor_id,
        'new_distributor_id' => $validated['new_distributor_id'],
        'reason'             => $validated['reason'],
    ]);

    return redirect()->route('requests.all')->with('success', '✅ تم عمل الطلب بنجاح.');
}

protected function applyChangeDistributor(RequestModel $request)
{
    if ($request->changeDistributor && $request->changeDistributor->new_distributor_id) {
        $request->line->update([
            'distributor_id' => $request->changeDistributor->new_distributor_id,
        ]);
    }
}


public function history(HttpRequest $request)
{
    $user = auth()->user();
    $isDistributor = $user->role && $user->role->name === 'موزع';

    $query = \App\Models\Request::with(['line.customer', 'requestedBy', 'doneBy', 'resellDetails'])
        ->whereIn('status', ['done', 'cancelled']);

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($isDistributor) {
        $query->whereHas('line', fn($q) => $q->where('distributor_id', $user->id));
    }


    // فلترة بالرقم
    if ($request->filled('phone')) {
        $query->whereHas('line', fn($q) => $q->where('phone_number', 'like', "%{$request->phone}%"));
    }

    // فلترة بالرقم القومي
    if ($request->filled('nid')) {
        $query->whereHas('line.customer', fn($q) => $q->where('national_id', 'like', "%{$request->nid}%"));
    }

    // فلترة بالنوع
    if ($request->filled('type')) {
        $query->where('request_type', $request->type);
    }

    // فلترة بالتاريخ
    if ($request->filled('from')) {
        $query->whereDate('created_at', '>=', $request->from);
    }
    if ($request->filled('to')) {
        $query->whereDate('created_at', '<=', $request->to);
    }

    // فلترة بالمشغل
    if ($request->filled('provider')) {
        $query->whereHas('line', fn($q) => $q->where('provider', 'like', "%{$request->provider}%"));
    }

    $requests = $query->latest()->paginate(20);

    return view('admin.requests.history', compact('requests'));
}

public function all(HttpRequest $request)
{
    $user = auth()->user();
    $isDistributor = $user->role && $user->role->name === 'موزع';

    $query = \App\Models\Request::with(['line.customer', 'stopDetails', 'resellDetails', 'changePlan', 'changeChip', 'pause', 'resume', 'changeDistributor', 'changeDate'])
        ->whereNotIn('status', ['done', 'cancelled']);

    if ($isDistributor) {
        $query->whereHas('line', fn($q) => $q->where('distributor_id', $user->id));
    }

    // فلترة بالرقم
    if ($request->filled('phone')) {
        $query->whereHas('line', fn($q) => $q->where('phone_number', 'like', "%{$request->phone}%"));
    }

    // فلترة بالرقم القومي
    if ($request->filled('nid')) {
        $query->whereHas('line.customer', fn($q) => $q->where('national_id', 'like', "%{$request->nid}%"));
    }

    // فلترة بالنوع
    if ($request->filled('type')) {
        $query->where('request_type', $request->type);
    }

    // فلترة بالتاريخ
    if ($request->filled('from')) {
        $query->whereDate('created_at', '>=', $request->from);
    }
    if ($request->filled('to')) {
        $query->whereDate('created_at', '<=', $request->to);
    }

    // فلترة بالمشغل
    if ($request->filled('provider')) {
        $query->whereHas('line', fn($q) => $q->where('provider', 'like', "%{$request->provider}%"));
    }

    $requests = $query->latest()->paginate(20);
    $plans = \App\Models\Plan::all();
    $distributors = \App\Models\User::whereHas('role', function($q) {
        $q->where('name', 'موزع');
    })->when($isDistributor, function($q) use ($user) {
        $q->where('id', $user->id);
    })->select('id', 'name')->get();

    return view('admin.requests.all', compact('requests', 'plans', 'distributors'));
}

public function updateDetails(HttpRequest $httpRequest, RequestModel $request)
{
    $type = $request->request_type;

    if ($type === 'stop') {
        $request->stopDetails()->update($httpRequest->only(['reason', 'comment']));
    } elseif ($type === 'resell') {
        $request->resellDetails()->update($httpRequest->only(['resell_type', 'old_serial', 'new_serial', 'request_date', 'full_name', 'national_id', 'comment', 'sale_price']));
    } elseif ($type === 'change_plan') {
        $request->changePlan()->update($httpRequest->only(['new_plan_id', 'comment']));
    } elseif ($type === 'change_chip') {
        $request->changeChip()->update($httpRequest->only(['change_type', 'old_serial', 'new_serial', 'full_name', 'national_id', 'request_date', 'comment']));
    } elseif ($type === 'pause') {
        $request->pause()->update($httpRequest->only(['reason', 'comment']));
    } elseif ($type === 'resume') {
        $request->resume()->update($httpRequest->only(['reason', 'comment']));
    } elseif ($type === 'change_distributor') {
        $request->changeDistributor()->update($httpRequest->only(['new_distributor_id', 'reason', 'comment']));
    } elseif ($type === 'change_date') {
        $request->changeDate()->update($httpRequest->only(['new_date', 'reason', 'comment']));
    }

    return back()->with('success', '✅ تم تحديث بيانات الطلب بنجاح.');
}

public function bulkUpdate(HttpRequest $request)
{
    $request->validate([
        'selected_requests' => 'required|array',
        'status' => 'required|in:pending,inprogress,done,cancelled',
    ]);

    $requests = RequestModel::whereIn('id', $request->selected_requests)->get();
    foreach ($requests as $req) {
        if ($request->status === 'done' && $req->status !== 'done') {
            $this->applyRequestEffects($req);
            $this->handleTargetRelease($req);
        }

        $req->update([
            'status' => $request->status,
            'done_by' => auth()->id(),
        ]);
    }

    return back()->with('success', '✅ تم تحديث حالة الطلبات المحددة بنجاح.');
}

public function bulkAction(HttpRequest $request)
{
    $request->validate([
        'selected_requests' => 'required|array',
        'selected_requests.*' => 'exists:requests,id',
        'new_status' => 'nullable|in:pending,inprogress,done,cancelled',
        'action' => 'required|in:change_status,export,change_and_export',
    ]);

    $requests = RequestModel::whereIn('id', $request->selected_requests)->get();

    if ($request->action === 'change_status' || $request->action === 'change_and_export') {
        foreach ($requests as $r) {
            if ($request->new_status === 'done' && $r->status !== 'done') {
                $this->applyRequestEffects($r);
                $this->handleTargetRelease($r);
            }
            $r->update([
                'status' => $request->new_status,
                'done_by' => auth()->id()
            ]);
        }
    }

    if ($request->action === 'export' || $request->action === 'change_and_export') {
        return Excel::download(new RequestsExport($requests), 'selected_requests.xlsx');
    }

    return back()->with('success', '✅ تم تنفيذ العملية بنجاح.');
}

public function show(RequestModel $request)
{
    if (auth()->user()->role?->name === 'موزع' && $request->line?->distributor_id !== auth()->id()) {
        abort(403, 'غير مصرح لك بمشاهدة هذا الطلب.');
    }

    $request->load([
        'line.customer',
        'requestedBy',
        'doneBy',
        'stopDetails',
        'resellDetails',
        'changeChip',
        'pause',
        'resume',
        'changePlan',
        'changeDistributor',
        'changeDate',
    ]);

    return view('admin.requests.show', compact('request'));
}

public function summary()
{
   $types = ['resell', 'change_plan', 'change_chip', 'pause', 'resume', 'change_date', 'change_distributor', 'stop'];

$counts = [];
foreach ($types as $type) {
        $counts[$type] = [
            'today' => \App\Models\Request::where('request_type', $type)
                            ->when(auth()->user()->role?->name === 'موزع', function($q) {
                                $q->whereHas('line', fn($lineQ) => $lineQ->where('distributor_id', auth()->id()));
                            })
                            ->whereDate('created_at', now()->toDateString())
                            ->count(),
            'total' => \App\Models\Request::where('request_type', $type)
                            ->when(auth()->user()->role?->name === 'موزع', function($q) {
                                $q->whereHas('line', fn($lineQ) => $lineQ->where('distributor_id', auth()->id()));
                            })
                            ->count(),
        ];
}

return view('admin.requests.summary', compact('counts'));

}
}
