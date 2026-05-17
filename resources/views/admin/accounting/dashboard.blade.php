<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 md:gap-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                {{ __('messages.accounting_dashboard') }}
            </h2>
            <form method="GET" action="{{ route('accounting.dashboard') }}" class="flex flex-wrap items-center gap-2 bg-white dark:bg-gray-800 p-3 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-1 px-2 border-r dark:border-gray-700">
                    <span class="text-xs font-bold text-gray-400">{{ __('messages.from') }}:</span>
                    <select name="from_month" class="input input-bordered p-1 text-sm text-gray-900 rounded border-gray-200">
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ $i }}" {{ $from_month == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    <input type="number" name="from_year" value="{{ $from_year }}" class="input input-bordered w-20 p-1 text-sm text-gray-900 rounded border-gray-200" />
                </div>
                
                <div class="flex items-center gap-1 px-2">
                    <span class="text-xs font-bold text-gray-400">{{ __('messages.to') }}:</span>
                    <select name="to_month" class="input input-bordered p-1 text-sm text-gray-900 rounded border-gray-200">
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ $i }}" {{ $to_month == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    <input type="number" name="to_year" value="{{ $to_year }}" class="input input-bordered w-20 p-1 text-sm text-gray-900 rounded border-gray-200" />
                </div>

                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 rounded-lg shadow-sm text-sm font-bold transition-all">
                    🔍 {{ __('messages.update_period') }}
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 break-words text-sm sm:text-base" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow font-bold text-center">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded shadow font-bold">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- إحصائيات عامة --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-8">
            {{-- الأرباح المتوقعة --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border-l-4 border-green-500">
                <div class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-1">{{ __('messages.period_net_profit') }}</div>
                <div class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ number_format($netProfit, 2) }} {{ __('messages.currency') }}</div>
                <div class="text-xs text-gray-400 mt-2">{{ __('messages.net_profit_desc') }}</div>
            </div>

            {{-- إيرادات الفواتير --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border-l-4 border-emerald-400">
                <div class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-1">{{ __('messages.invoice_revenues') }}</div>
                <div class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ number_format($invoiceProfits, 2) }} {{ __('messages.currency') }}</div>
            </div>

            {{-- رأس المال --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border-l-4 border-blue-500">
                <div class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-1">{{ __('messages.total_capital') }}</div>
                <div class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ number_format($totalCapital, 2) }} {{ __('messages.currency') }}</div>
            </div>

            {{-- إجمالي المصروفات للفترة --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border-l-4 border-red-500">
                <div class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-1">{{ __('messages.total_period_expenses') }}</div>
                <div class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ number_format($monthlyExpenses, 2) }} {{ __('messages.currency') }}</div>
                <div class="mt-2 space-y-1">
                    <div class="text-[10px] text-gray-500 flex justify-between">
                        <span>{{ __('messages.misc_expenses') }}:</span>
                        <span class="font-bold">{{ number_format($directExpenses, 2) }} {{ __('messages.currency') }}</span>
                    </div>
                    <div class="text-[10px] text-gray-500 flex justify-between">
                        <span>{{ __('messages.line_purchases') }}:</span>
                        <span class="font-bold">{{ number_format($linesPurchaseCost, 2) }} {{ __('messages.currency') }}</span>
                    </div>
                </div>
            </div>

            {{-- إجمالي الرواتب --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border-l-4 border-yellow-500">
                <div class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-1">{{ __('messages.total_period_salaries') }}</div>
                <div class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ number_format($salaries, 2) }} {{ __('messages.currency') }}</div>
            </div>

            {{-- إجمالي السلف --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border-l-4 border-purple-500">
                <div class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-1">{{ __('messages.total_period_advances') }}</div>
                <div class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ number_format($totalAdvances, 2) }} {{ __('messages.currency') }}</div>
            </div>

            {{-- عدد العمليات المكتملة --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border-l-4 border-indigo-400">
                <div class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-1">العمليات المكتملة</div>
                <div class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $completedRequestsCount + $directSalesCount }}</div>
                <div class="mt-2 text-[10px] text-gray-500 flex justify-between">
                    <span>طلبات: {{ $completedRequestsCount }} | مباشر: {{ $directSalesCount }}</span>
                </div>
            </div>
        </div>



        {{-- الخطوط المباعة بيع نهائي في هذه الفترة --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-700 mb-8">
            <div class="bg-gray-100 dark:bg-gray-700 p-4 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 dark:text-gray-200">{{ __('messages.completed_line_sales') }}</h3>
                <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full">{{ __('messages.total_profit_item') }} {{ number_format($soldLinesList->sum('calculated_profit'), 2) }} {{ __('messages.currency') }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="p-3">{{ __('messages.phone_number') }}</th>
                            <th class="p-3">النوع</th>
                            <th class="p-3">{{ __('messages.request_date') }}</th>
                            <th class="p-3">{{ __('messages.buy_price') }}</th>
                            <th class="p-3">{{ __('messages.sale_price') }}</th>
                            <th class="p-3">{{ __('messages.net_profit') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($soldLinesList as $soldLine)
                        <tr class="border-b dark:border-gray-700">
                            <td class="p-3 font-mono text-gray-900 dark:text-gray-200">{{ $soldLine->phone_number }}</td>
                            <td class="p-3">
                                @if(isset($soldLine->is_direct) && $soldLine->is_direct)
                                    <span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded-full text-[10px] font-black">مباشر</span>
                                @else
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-[10px] font-black">طلب</span>
                                @endif
                            </td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($soldLine->display_date ?? $soldLine->updated_at)->format('Y-m-d') }}</td>
                            <td class="p-3 text-red-500 font-bold">-{{ number_format($soldLine->display_buy_price ?? $soldLine->buy_price, 2) }}</td>
                            <td class="p-3 text-green-600 font-bold">+{{ number_format($soldLine->display_sale_price ?? $soldLine->sale_price, 2) }}</td>
                            <td class="p-3 text-indigo-600 font-bold">{{ number_format($soldLine->calculated_profit, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center">{{ __('messages.no_final_sales_recorded') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                {{ $soldLinesList->appends(request()->query())->links() }}
            </div>
        </div>
        </div>

        {{-- تفاصيل الفواتير المحصلة في هذه الفترة --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-700 mb-8">
            <div class="bg-gray-100 dark:bg-gray-700 p-4 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 dark:text-gray-200">{{ __('messages.paid_invoices_breakdown') }}</h3>
                <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full">{{ __('messages.total_profit_item') }} {{ number_format($invoiceProfits, 2) }} {{ __('messages.currency') }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr class="divide-x dark:divide-gray-600">
                            <th class="p-3">{{ __('messages.phone_number') }}</th>
                            <th class="p-3">{{ __('messages.service_month') }}</th>
                            <th class="p-3">{{ __('messages.payment_date') }}</th>
                            <th class="p-3">{{ __('messages.amount') }}</th>
                            <th class="p-3">{{ __('messages.net_profit') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paidInvoices as $invoice)
                        <tr class="border-b dark:border-gray-700 {{ $invoice->calculated_profit == 0 ? 'bg-orange-50 dark:bg-orange-900/10' : '' }}">
                            <td class="p-3 font-mono text-gray-900 dark:text-gray-200">{{ $invoice->line->phone_number ?? '---' }}</td>
                            <td class="p-3 text-xs">{{ \Carbon\Carbon::parse($invoice->invoice_month)->format('Y-m') }}</td>
                            <td class="p-3 font-bold text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($invoice->payment_date)->format('Y-m-d H:i:s') }}</td>
                            <td class="p-3">{{ number_format($invoice->amount, 2) }}</td>
                            <td class="p-3 {{ $invoice->calculated_profit <= 0 ? 'text-red-500 font-black' : 'text-emerald-600 font-bold' }}">
                                {{ number_format($invoice->calculated_profit, 2) }}
                                @if($invoice->calculated_profit <= 0)
                                    <span class="block text-[8px] text-orange-600">⚠️ راجع الخطة</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-400 italic">لا توجد فواتير محصلة في هذا النطاق الزمني</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                {{ $paidInvoices->appends(request()->query())->links() }}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            {{-- نموذج تسجيل بيع مباشر --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow border border-purple-100 dark:border-purple-900/30">
                <h3 class="text-xl font-bold mb-4 text-purple-700 dark:text-purple-400 flex items-center gap-2">
                    <span>🛒</span> تسجيل بيع مباشر
                </h3>
                <form method="POST" action="{{ route('accounting.direct-sale.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">اختر الخط</label>
                        <select name="line_id" required class="w-full border p-2 rounded-xl text-gray-900 font-bold text-sm">
                            <option value="">-- اختر خط --</option>
                            @foreach($allLines as $line)
                                <option value="{{ $line->id }}">{{ $line->phone_number }} ({{ $line->provider }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">اختر العميل</label>
                        <select name="customer_id" required class="w-full border p-2 rounded-xl text-gray-900 font-bold text-sm">
                            <option value="">-- اختر عميل --</option>
                            @foreach($allCustomers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">سعر البيع</label>
                            <input type="number" step="0.01" name="sale_price" required class="w-full border p-2 rounded-xl text-gray-900 font-bold" />
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">التاريخ</label>
                            <input type="date" name="sale_date" value="{{ date('Y-m-d') }}" required class="w-full border p-2 rounded-xl text-gray-900 text-xs font-bold" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">ملاحظات</label>
                        <input type="text" name="notes" class="w-full border p-2 rounded-xl text-gray-900" placeholder="اختياري" />
                    </div>
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-xl shadow-lg shadow-purple-500/20 w-full font-black uppercase tracking-widest text-xs transition-all active:scale-[0.98]">
                        إتمام البيع المباشر
                    </button>
                </form>
            </div>
            {{-- نموذج إضافة رأس مال --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">{{ __('messages.add_capital') }}</h3>
                <form method="POST" action="{{ route('accounting.capital.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.amount') }} ({{ __('messages.currency') }})</label>
                        <input type="number" step="0.01" name="amount" required class="w-full border p-2 rounded text-gray-900" />
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.date') }}</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full border p-2 rounded text-gray-900" />
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.description_note') }}</label>
                        <input type="text" name="description" class="w-full border p-2 rounded text-gray-900" placeholder="{{ __('messages.example_partner') }}" />
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow w-full font-bold">
                        {{ __('messages.save') }}
                    </button>
                </form>
            </div>

            {{-- نموذج إضافة مصروف (نثرية) --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">{{ __('messages.register_expense') }}</h3>
                <form method="POST" action="{{ route('accounting.expense.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.amount') }} ({{ __('messages.currency') }})</label>
                        <input type="number" step="0.01" name="amount" required class="w-full border p-2 rounded text-gray-900" />
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.category_classification') }}</label>
                        <select name="category" class="w-full border p-2 rounded text-gray-900">
                            <option value="نسريات (شاي قهوة سكر)">{{ __('messages.cat_misc') }}</option>
                            <option value="وجبات">{{ __('messages.cat_meals') }}</option>
                            <option value="مواصلات">{{ __('messages.cat_transport') }}</option>
                            <option value="صيانة">{{ __('messages.cat_maintenance') }}</option>
                            <option value="أخرى">{{ __('messages.cat_other') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.date') }}</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full border p-2 rounded text-gray-900" />
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.expense_details') }}</label>
                        <input type="text" name="description" class="w-full border p-2 rounded text-gray-900" placeholder="{{ __('messages.example_sugar_tea') }}" />
                    </div>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow w-full font-bold">
                        {{ __('messages.submit') }}
                    </button>
                </form>
            </div>
        </div>

        {{-- عرض آخر المصروفات ورأس المال --}}
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            
            {{-- جدول آخر المصروفات --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="bg-gray-100 dark:bg-gray-700 p-4 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="font-bold text-gray-800 dark:text-gray-200">{{ __('messages.recent_expenses') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="p-3">{{ __('messages.date') }}</th>
                                <th class="p-3">{{ __('messages.amount') }}</th>
                                <th class="p-3">{{ __('messages.category_classification') }}</th>
                                <th class="p-3">{{ __('messages.added_by') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentExpenses as $exp)
                            <tr class="border-b dark:border-gray-700">
                                <td class="p-3">{{ $exp->date }}</td>
                                <td class="p-3 font-bold {{ $exp->amount >= 0 ? 'text-red-500' : 'text-emerald-500' }}">
                                    {{ $exp->amount >= 0 ? '-' : '+' }}{{ number_format(abs($exp->amount), 2) }}
                                </td>
                                <td class="p-3">{{ $exp->category }}</td>
                                <td class="p-3">{{ $exp->user->name ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="p-4 text-center">لا توجد مصروفات مسجلة</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                    {{ $recentExpenses->appends(request()->query())->links() }}
                </div>
            </div>

            {{-- جدول إيداعات رأس المال --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="bg-gray-100 dark:bg-gray-700 p-4 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="font-bold text-gray-800 dark:text-gray-200">{{ __('messages.capital_deposits_log') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="p-3">{{ __('messages.date') }}</th>
                                <th class="p-3">{{ __('messages.amount') }}</th>
                                <th class="p-3">{{ __('messages.notes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($capitals as $cap)
                            <tr class="border-b dark:border-gray-700">
                                <td class="p-3">{{ $cap->date }}</td>
                                <td class="p-3 font-bold {{ $cap->amount >= 0 ? 'text-blue-500' : 'text-orange-500' }}">
                                    {{ $cap->amount >= 0 ? '+' : '-' }}{{ number_format(abs($cap->amount), 2) }}
                                </td>
                                <td class="p-3">{{ $cap->description }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="p-4 text-center">لا يوجد رصيد مسجل</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                    {{ $capitals->appends(request()->query())->links() }}
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
