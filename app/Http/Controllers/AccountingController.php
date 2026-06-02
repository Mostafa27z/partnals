<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Capital;
use App\Models\Expense;
use App\Models\Line;
use App\Models\Salary;
use App\Models\Advance;
use App\Models\Invoice;
use App\Models\DirectSale;
use App\Models\RequestResell;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Maatwebsite\Excel\Facades\Excel;

class AccountingController extends Controller
{
    public function dashboard(Request $request)
    {
        $fromMonth = $request->input('from_month', now()->month);
        $fromYear  = $request->input('from_year', now()->year);
        $toMonth   = $request->input('to_month', now()->month);
        $toYear    = $request->input('to_year', now()->year);

        $startDate = Carbon::create($fromYear, $fromMonth, 1)->startOfMonth();
        $endDate   = Carbon::create($toYear, $toMonth, 1)->endOfMonth();

        if ($startDate->gt($endDate)) {
            // Swap if user selected to_date before from_date
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        // 1. Total Capital (Always total)
        $totalCapital = Capital::sum('amount');

        // 2. Expenses for the selected period
        $directExpenses = Expense::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
                                  ->sum('amount');
                                  
        $linesPurchaseCost = Line::whereBetween('created_at', [$startDate, $endDate])
                                  ->sum('buy_price');

        $monthlyExpenses = $directExpenses + $linesPurchaseCost;
                                  
        $totalExpenses = Expense::sum('amount') + Line::sum('buy_price');

        // 3. Revenues from Lines
        $totalSalesRevenue = 0;
        $soldLinesList = collect();

        // 3a. Recurring Monthly Profits (from ACTUAL PAID INVOICES)
        // Replacing the theoretical calculations with actual collected revenues snapshots
        $invoiceQuery = Invoice::where('is_paid', true)
            ->whereBetween('payment_date', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()]);
            
        // Invoices are already margins (Profit), so we keep them as a separate component for the final sum
        $invoiceProfits = (float) $invoiceQuery->sum('calculated_profit');
        
        $paidInvoices = $invoiceQuery->with(['line', 'line.plan'])
            ->latest('payment_date')
            ->paginate(20, ['*'], 'invoices_page');

        // 3b. One-time Sales Profits from Completed Resells (Using IMMUTABLE Snapshots)
        $completedResells = \App\Models\RequestResell::where('is_sold', true)
        ->whereHas('request', function($q) use ($startDate, $endDate) {
            $q->where('status', 'done')
              ->whereBetween('updated_at', [$startDate, $endDate]);
        })->with('request.line')->get();

        foreach ($completedResells as $resell) {
            $salePrice = (float)$resell->sale_price;
            $buyPrice  = (float)$resell->buy_price;
            $profit    = $salePrice - $buyPrice;
            
            $totalSalesRevenue += $salePrice;
            
            // Add to the list for the dashboard table
            $soldLine = $resell->request->line;
            if ($soldLine) {
                $soldLine->calculated_profit = $profit; // Now represents true profit Margin
                $soldLine->display_buy_price = $buyPrice;
                $soldLine->display_sale_price = $salePrice;
                $soldLine->sale_source = 'resell';
                $soldLine->sale_source_id = $resell->id;
                // Update dates to match the resell completion date
                $soldLine->display_date = $resell->request->updated_at;
                $soldLinesList->push($soldLine);
            }
        }

        // 3c. One-time Sales Profits from New Assignments (Initial sales)
        // Note: These are still somewhat volatile as we don't have a snapshot for "assignment" yet,
        // but we'll include them to keep the logic complete.
        $assignments = Line::whereBetween('attached_at', [$startDate, $endDate])
                           ->where('is_sold', false) // Not already counted in 3b
                           ->get();
        foreach ($assignments as $line) {
            $totalSalesRevenue += (float)$line->sale_price;
        }

        // 3d. One-time Sales Profits from Direct Sales (New direct feature)
        $directSales = DirectSale::whereBetween('sale_date', [$startDate->toDateString(), $endDate->toDateString()])
                                 ->with('line')
                                 ->get();
        
        foreach ($directSales as $sale) {
            $totalSalesRevenue += (float)$sale->sale_price;
            
            // Add to the list for the dashboard table
            $soldLine = $sale->line;
            if ($soldLine) {
                $soldLine->calculated_profit = (float)$sale->profit;
                $soldLine->display_buy_price = (float)$sale->buy_price;
                $soldLine->display_sale_price = (float)$sale->sale_price;
                $soldLine->sale_source = 'direct';
                $soldLine->sale_source_id = $sale->id;
                $soldLine->display_date = $sale->sale_date;
                $soldLine->is_direct = true;
                $soldLinesList->push($soldLine);
            }
        }

        // --- Manual Pagination for soldLinesList ---
        $currentPage = Paginator::resolveCurrentPage('sales_page');
        $perPage = 10;
        $pagedData = $soldLinesList->sortByDesc('display_date')->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $soldLinesListPaginated = new LengthAwarePaginator($pagedData, $soldLinesList->count(), $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'sales_page',
        ]);

        // 3d. Total Advances for the selected period
        $totalAdvances = Advance::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
                                ->sum('amount');

        // 4. Salaries for the selected period
        // ... (existing salaries logic) ...
        $startVal = $startDate->year * 12 + $startDate->month;
        $endVal   = $endDate->year * 12 + $endDate->month;
        $salaries = Salary::whereRaw('(year * 12 + month) >= ? AND (year * 12 + month) <= ?', [$startVal, $endVal])
                          ->sum('amount');
        
        // 5. Net Profit (Cash Flow Basis)
        // Net Profit = (Invoice Profits) + (Total Sales Revenue) - (Total Line Purchase Costs) - (Misc Expenses) - (Salaries) - (Advances)
        $netProfit = $invoiceProfits + $totalSalesRevenue - $linesPurchaseCost - $directExpenses - $salaries - $totalAdvances;

        // Data for tables
        $recentExpenses = Expense::with('user')->orderBy('date', 'desc')->paginate(10, ['*'], 'expenses_page');
        $capitals = Capital::orderBy('date', 'desc')->paginate(10, ['*'], 'capitals_page');

        return view('admin.accounting.dashboard', [
            'from_month' => $fromMonth,
            'from_year'  => $fromYear,
            'to_month'   => $toMonth,
            'to_year'    => $toYear,
            'totalCapital' => $totalCapital,
            'monthlyExpenses' => $directExpenses + $linesPurchaseCost, 
            'totalExpenses' => Expense::sum('amount') + Line::sum('buy_price'),
            'expectedProfits' => $totalSalesRevenue, // Will display as Total Sales Revenue in UI if updated, but keeping key for now
            'invoiceProfits' => $invoiceProfits,
            'paidInvoices' => $paidInvoices,
            'salaries' => $salaries,
            'netProfit' => $netProfit,
            'recentExpenses' => $recentExpenses,
            'capitals' => $capitals,
            'soldLinesList' => $soldLinesListPaginated,
            'directExpenses' => $directExpenses,
            'totalAdvances' => $totalAdvances,
            'linesPurchaseCost' => $linesPurchaseCost,
            'completedRequestsCount' => \App\Models\Request::where('status', 'done')->whereBetween('updated_at', [$startDate, $endDate])->count(),
            'directSalesCount' => $directSales->count(),
            'allLines' => Line::where('is_sold', false)->get(),
            'allCustomers' => \App\Models\Customer::orderBy('full_name')->get(),
        ]);
    }

    public function storeCapital(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date'
        ]);

        Capital::create($request->all());

        return redirect()->back()->with('success', 'تم إضافة رأس المال بنجاح.');
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'description' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'date' => 'required|date'
        ]);

        Expense::create([
            'amount' => $request->amount,
            'description' => $request->description,
            'category' => $request->category,
            'date' => $request->date,
            'user_id' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'تم تسجيل المصروف بنجاح.');
    }

    public function updateSalePrices(Request $request)
    {
        $validated = $request->validate([
            'sale_source' => 'required|in:resell,direct',
            'sale_source_id' => 'required|integer',
            'buy_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
        ]);

        if ($validated['sale_source'] === 'direct') {
            $sale = DirectSale::findOrFail($validated['sale_source_id']);
            $sale->update([
                'buy_price' => $validated['buy_price'],
                'sale_price' => $validated['sale_price'],
                'profit' => $validated['sale_price'] - $validated['buy_price'],
            ]);

            if ($sale->line) {
                $sale->line->update(['sale_price' => $validated['sale_price']]);
            }
        } else {
            $resell = RequestResell::findOrFail($validated['sale_source_id']);
            $resell->update([
                'buy_price' => $validated['buy_price'],
                'sale_price' => $validated['sale_price'],
            ]);

            if ($resell->request && $resell->request->line) {
                $resell->request->line->update(['sale_price' => $validated['sale_price']]);
            }
        }

        return redirect()->back()->with('success', '✅ تم تحديث سعر الشراء والبيع بنجاح.');
    }

    public function exportCompletedSales(Request $request)
    {
        $fromMonth = $request->input('from_month', now()->month);
        $fromYear  = $request->input('from_year', now()->year);
        $toMonth   = $request->input('to_month', now()->month);
        $toYear    = $request->input('to_year', now()->year);

        $startDate = Carbon::create($fromYear, $fromMonth, 1)->startOfMonth();
        $endDate   = Carbon::create($toYear, $toMonth, 1)->endOfMonth();

        if ($startDate->gt($endDate)) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        $rows = collect();

        $completedResells = RequestResell::where('is_sold', true)
            ->whereHas('request', function ($q) use ($startDate, $endDate) {
                $q->where('status', 'done')
                  ->whereBetween('updated_at', [$startDate, $endDate]);
            })
            ->with('request.line')
            ->get();

        foreach ($completedResells as $resell) {
            $line = $resell->request->line;
            if (!$line) {
                continue;
            }

            $rows->push([
                'phone_number' => $line->phone_number,
                'type' => 'Request Resell',
                'date' => optional($resell->request->updated_at)->format('Y-m-d'),
                'source' => 'Resell',
                'buy_price' => $resell->buy_price,
                'sale_price' => $resell->sale_price,
                'profit' => $resell->sale_price - $resell->buy_price,
            ]);
        }

        $directSales = DirectSale::whereBetween('sale_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->with('line')
            ->get();

        foreach ($directSales as $sale) {
            $rows->push([
                'phone_number' => $sale->line->phone_number ?? 'N/A',
                'type' => 'Direct Sale',
                'date' => $sale->sale_date,
                'source' => 'Direct Sale',
                'buy_price' => $sale->buy_price,
                'sale_price' => $sale->sale_price,
                'profit' => $sale->sale_price - $sale->buy_price,
            ]);
        }

        return Excel::download(new class($rows) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $rows;

            public function __construct($rows)
            {
                $this->rows = $rows;
            }

            public function collection()
            {
                return $this->rows;
            }

            public function headings(): array
            {
                return ['Phone Number', 'Type', 'Date', 'Source', 'Buy Price', 'Sale Price', 'Net Profit'];
            }
        }, "completed-sales-{$startDate->format('Ymd')}-{$endDate->format('Ymd')}.xlsx");
    }

    public function exportPaidInvoices(Request $request)
    {
        $fromMonth = $request->input('from_month', now()->month);
        $fromYear  = $request->input('from_year', now()->year);
        $toMonth   = $request->input('to_month', now()->month);
        $toYear    = $request->input('to_year', now()->year);

        $startDate = Carbon::create($fromYear, $fromMonth, 1)->startOfMonth();
        $endDate   = Carbon::create($toYear, $toMonth, 1)->endOfMonth();

        if ($startDate->gt($endDate)) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        $invoices = Invoice::where('is_paid', true)
            ->whereBetween('payment_date', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->with(['line', 'line.plan'])
            ->latest('payment_date')
            ->get();

        $rows = $invoices->map(function ($invoice) {
            return [
                'phone_number' => $invoice->line->phone_number ?? 'N/A',
                'plan_name' => $invoice->line->plan->name ?? 'N/A',
                'service_month' => $invoice->invoice_month ? Carbon::parse($invoice->invoice_month)->format('Y-m') : 'N/A',
                'payment_date' => $invoice->payment_date ? Carbon::parse($invoice->payment_date)->format('Y-m-d H:i:s') : 'N/A',
                'amount' => $invoice->amount,
                'profit' => $invoice->calculated_profit,
            ];
        });

        return Excel::download(new class($rows) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $rows;

            public function __construct($rows)
            {
                $this->rows = $rows;
            }

            public function collection()
            {
                return $this->rows;
            }

            public function headings(): array
            {
                return ['Phone Number', 'Plan', 'Service Month', 'Payment Date', 'Amount', 'Profit'];
            }
        }, "paid-invoices-{$startDate->format('Ymd')}-{$endDate->format('Ymd')}.xlsx");
    }

    public function exportExpenses(Request $request)
    {
        $fromMonth = $request->input('from_month', now()->month);
        $fromYear  = $request->input('from_year', now()->year);
        $toMonth   = $request->input('to_month', now()->month);
        $toYear    = $request->input('to_year', now()->year);

        $startDate = Carbon::create($fromYear, $fromMonth, 1)->startOfMonth();
        $endDate   = Carbon::create($toYear, $toMonth, 1)->endOfMonth();

        if ($startDate->gt($endDate)) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        $expenses = Expense::with('user')
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('date', 'desc')
            ->get();

        $rows = $expenses->map(function ($expense) {
            return [
                'date' => $expense->date,
                'amount' => $expense->amount,
                'category' => $expense->category,
                'description' => $expense->description,
                'added_by' => $expense->user->name ?? 'N/A',
            ];
        });

        return Excel::download(new class($rows) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $rows;

            public function __construct($rows)
            {
                $this->rows = $rows;
            }

            public function collection()
            {
                return $this->rows;
            }

            public function headings(): array
            {
                return ['Date', 'Amount', 'Category', 'Description', 'Added By'];
            }
        }, "expenses-{$startDate->format('Ymd')}-{$endDate->format('Ymd')}.xlsx");
    }

    public function exportCapitals(Request $request)
    {
        $fromMonth = $request->input('from_month', now()->month);
        $fromYear  = $request->input('from_year', now()->year);
        $toMonth   = $request->input('to_month', now()->month);
        $toYear    = $request->input('to_year', now()->year);

        $startDate = Carbon::create($fromYear, $fromMonth, 1)->startOfMonth();
        $endDate   = Carbon::create($toYear, $toMonth, 1)->endOfMonth();

        if ($startDate->gt($endDate)) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        $capitals = Capital::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('date', 'desc')
            ->get();

        $rows = $capitals->map(function ($capital) {
            return [
                'date' => $capital->date,
                'amount' => $capital->amount,
                'description' => $capital->description,
            ];
        });

        return Excel::download(new class($rows) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $rows;

            public function __construct($rows)
            {
                $this->rows = $rows;
            }

            public function collection()
            {
                return $this->rows;
            }

            public function headings(): array
            {
                return ['Date', 'Amount', 'Description'];
            }
        }, "capital-deposits-{$startDate->format('Ymd')}-{$endDate->format('Ymd')}.xlsx");
    }

    public function storeDirectSale(Request $request)
    {
        $request->validate([
            'line_id' => 'required|exists:lines,id',
            'customer_id' => 'required|exists:customers,id',
            'sale_price' => 'required|numeric|min:0',
            'sale_date' => 'required|date',
            'notes' => 'nullable|string|max:1000'
        ]);

        $line = Line::findOrFail($request->line_id);
        
        $buyPrice = (float)$line->buy_price;
        $salePrice = (float)$request->sale_price;
        $profit = $salePrice - $buyPrice;

        // 1. Record the sale
        DirectSale::create([
            'line_id' => $line->id,
            'customer_id' => $request->customer_id,
            'user_id' => auth()->id(),
            'buy_price' => $buyPrice,
            'sale_price' => $salePrice,
            'profit' => $profit,
            'sale_date' => $request->sale_date,
            'notes' => $request->notes
        ]);

        // 2. Update the line
        $line->update([
            'customer_id' => $request->customer_id,
            'sale_price' => $salePrice,
            'is_sold' => true,
            'attached_at' => $request->sale_date
        ]);

        return redirect()->back()->with('success', '✅ تم تسجيل عملية البيع المباشر بنجاح.');
    }
}
