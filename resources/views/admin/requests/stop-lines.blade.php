<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">طلبات إيقاف الخطوط</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- الرسائل --}}
        @if ($errors->has('status'))
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/30 text-red-800 dark:text-red-400 border border-red-200 dark:border-red-800 rounded-xl shadow-sm">
                {{ $errors->first('status') }}
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/30 text-green-800 dark:text-green-400 border border-green-200 dark:border-green-800 rounded-xl shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 mb-6">
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end" dir="rtl">
                <input type="text" name="nid" value="{{ request('nid') }}" placeholder="الرقم القومي" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                <input type="text" name="phone" value="{{ request('phone') }}" placeholder="رقم الهاتف" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">

                <select name="requested_by" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                    <option value="">-- أنشئ بواسطة --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request('requested_by') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>

                <select name="done_by" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                    <option value="">-- تم بواسطة --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request('done_by') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>

                <input type="date" name="from" value="{{ request('from') }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all" placeholder="من تاريخ">
                <input type="date" name="to" value="{{ request('to') }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all" placeholder="إلى تاريخ">

                <select name="provider" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                    <option value="">-- مزود الخدمة --</option>
                    @foreach(['Vodafone', 'Etisalat', 'Orange', 'WE'] as $provider)
                        <option value="{{ $provider }}" {{ request('provider') == $provider ? 'selected' : '' }}>{{ $provider }}</option>
                    @endforeach
                </select>

                <input type="text" name="distributor" value="{{ request('distributor') }}" placeholder="الموزع" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">

                <div class="lg:col-span-4 flex justify-end pt-2">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3 rounded-xl shadow-lg shadow-blue-500/20 transition-all active:scale-95 flex items-center gap-2">
                        <span>🔍 بحث</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden" dir="rtl">
            <div class="overflow-x-auto">
                @if($requests->count())
                    <table class="min-w-full text-center divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400">
                            <tr class="text-xs uppercase tracking-wider font-black">
                                <th class="px-4 py-4">العميل</th>
                                <th class="px-4 py-4">الرقم القومي</th>
                                <th class="px-4 py-4">رقم الخط</th>
                                <th class="px-4 py-4">نوع الطلب</th>
                                <th class="px-4 py-4">الموزع</th>
                                <th class="px-4 py-4">المزود</th>
                                <th class="px-4 py-4">تاريخ آخر فاتورة</th>
                                <th class="px-4 py-4">أنشئ بواسطة</th>
                                <th class="px-4 py-4">تم بواسطة</th>
                                <th class="px-4 py-4">تغيير الحالة </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 font-medium">
                            @foreach ($requests as $request)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/40 transition-all border-b dark:border-gray-700/50">
                                    <td class="px-4 py-4 text-gray-900 dark:text-gray-200">{{ $request->line->customer->full_name ?? '-' }}</td>
                                    <td class="px-4 py-4 text-gray-600 dark:text-gray-400 font-mono text-sm">{{ $request->line->customer->national_id ?? '-' }}</td>
                                    <td class="px-4 py-4 text-blue-600 dark:text-blue-400 font-black">{{ $request->line->phone_number }}</td>
                                    <td class="px-4 py-4">
                                        <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs font-bold text-gray-600 dark:text-gray-300">
                                            {{ $request->request_type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-gray-600 dark:text-gray-400">{{ $request->line->distributor ?? '-' }}</td>
                                    <td class="px-4 py-4 text-gray-600 dark:text-gray-400">{{ $request->line->provider ?? '-' }}</td>

                                    <td class="px-4 py-4 text-gray-600 dark:text-gray-400">{{ $request->stopDetails->last_invoice_date ?? '-' }}</td>
                                    <td class="px-4 py-4 text-gray-600 dark:text-gray-400">{{ $request->requestedBy?->name ?? 'System' }}</td>
                                    <td class="px-4 py-4 text-gray-600 dark:text-gray-400">{{ $request->doneBy?->name ?? '-' }}</td>
                                    <td class="px-4 py-4">
                                        <form action="{{ route('requests.update-status', $request->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من تغيير الحالة؟')">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="old_status" value="{{ $request->status }}">
                                            <select name="status" class="px-3 py-1.5 rounded-xl border-0 font-bold text-xs ring-1 ring-inset
                                                @if($request->status == 'pending') bg-yellow-50 text-yellow-700 ring-yellow-600/20 dark:bg-yellow-900/30 dark:text-yellow-400 
                                                @elseif($request->status == 'inprogress') bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-900/30 dark:text-blue-400 
                                                @elseif($request->status == 'done') bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-900/30 dark:text-green-400 
                                                @endif"
                                                onchange="this.form.submit()">
                                                <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>معلق</option>
                                                <option value="inprogress" {{ $request->status == 'inprogress' ? 'selected' : '' }}>تحت التنفيذ</option>
                                                <option value="done" {{ $request->status == 'done' ? 'selected' : '' }}>تم</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                <div class="mt-4">{{ $requests->links() }}</div>
            @else
                <p class="text-gray-600 dark:text-gray-400">لا توجد طلبات حالياً.</p>
            @endif
        </div>
    </div>
</x-app-layout>
