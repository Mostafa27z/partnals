<x-app-layout>  
    <x-slot name="header">  
        <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 flex items-center gap-3">
            <span class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                <span class="text-white text-lg">📞</span>
            </span>
            {{ __('messages.line_details') }}
        </h2>  
    </x-slot>  

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6" dir="rtl">

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/30 text-emerald-700 dark:text-emerald-300 rounded-2xl shadow-sm flex items-center gap-3 font-bold">
                <span class="text-lg">✅</span>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800/30 text-rose-700 dark:text-rose-300 rounded-2xl shadow-sm space-y-2">
                <strong class="block font-bold">⚠️ يرجى تصحيح الأخطاء التالية:</strong>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Search Section --}}
        <div class="p-6 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700/50">
            <h3 class="text-lg font-black text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                🔍 {{ __('messages.search') ?? 'البحث' }}
            </h3>
            <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap gap-4 items-end">
                <!-- Phone (الرقم) -->
                <div class="w-full md:flex-1 min-w-[200px]">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2">الرقم (الهاتف)</label>
                    <input type="text" name="phone" value="" placeholder="مثال: 01012345678" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition placeholder:text-gray-400 text-sm" />
                </div>
                <!-- National ID (الرقم القومي) -->
                <div class="w-full md:flex-1 min-w-[200px]">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2">الرقم القومي</label>
                    <input type="text" name="nid" value="" placeholder="14 رقم" maxlength="14" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition placeholder:text-gray-400 text-sm" />
                </div>
                <!-- Customer Name (اسم العميل) -->
                <div class="w-full md:flex-1 min-w-[200px]">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2">اسم العميل</label>
                    <input type="text" name="customer_name" value="" placeholder="الاسم الكامل" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition placeholder:text-gray-400 text-sm" />
                </div>
                
                <div class="w-full md:w-auto">
                    <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25 cursor-pointer">
                        🔍 بحث
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 space-y-8">  
            
            <!-- Details Grid in specified order -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-gray-700 dark:text-gray-300">  
                <!-- رقم الهاتف -->
                <div class="bg-indigo-50/50 dark:bg-indigo-900/10 p-4 rounded-xl border border-indigo-100 dark:border-indigo-800/30">
                    <p class="text-[10px] font-black uppercase tracking-widest text-indigo-500 dark:text-indigo-400 mb-1">رقم الهاتف</p>
                    <p class="font-mono font-black text-indigo-700 dark:text-indigo-300 text-lg">{{ $line->phone_number }}</p>
                </div>

                <!-- اسم العميل -->
                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">اسم العميل</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->customer->full_name ?? '-' }}</p>
                </div>

                <!-- البطاقة -->
                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">البطاقة (الرقم القومي)</p>
                    <p class="font-mono font-bold text-gray-800 dark:text-gray-200">{{ $line->customer->national_id ?? '-' }}</p>
                </div>

                <!-- النظام -->
                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">النظام</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->plan->name ?? '-' }}</p>
                </div>

                <!-- تاريخ الفاتورة القادمة -->
                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">تاريخ الفاتورة القادمة</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->last_invoice_date ?? '-' }}</p>
                </div>

                <!-- العنوان -->
                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">العنوان</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->customer->address ?? '-' }}</p>
                </div>

                <!-- رقم تواصل العميل -->
                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">رقم تواصل العميل</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->customer->contact_number ?? '-' }}</p>
                </div>

                <!-- واتساب -->
                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">واتساب</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->customer->whatsapp_number ?? '-' }}</p>
                </div>

                <!-- تاريخ الميلاد -->
                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">تاريخ الميلاد</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->customer->birth_date ?? '-' }}</p>
                </div>

                <!-- الموزع -->
                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">الموزع</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->distributor->name ?? '-' }}</p>
                </div>

                <!-- رقم الشريحة المسلسل -->
                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">رقم الشريحة المسلسل</p>
                    <p class="font-mono font-bold text-gray-800 dark:text-gray-200">{{ $line->serial_number ?? '-' }}</p>
                </div>

                <!-- ملاحظات (أكثر من خانة) -->
                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 transition-colors col-span-1 sm:col-span-2 lg:col-span-3">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">ملاحظات</p>
                    <div class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200/60 dark:border-gray-700/50 text-gray-800 dark:text-gray-200 min-h-[80px] whitespace-pre-line">
                        {{ $line->notes ?? '-' }}
                    </div>
                </div>
            </div>

            <!-- Pay & Request Buttons & Update Actions Section -->
            <div x-data="{ showUpdateModal: false }" class="pt-6 border-t border-gray-100 dark:border-gray-700/50 flex flex-wrap gap-4 items-center">
                <!-- زر الدفع -->
                <a href="{{ route('invoices.create', $line) }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-600 text-white font-black text-sm hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-500/25 active:scale-95">
                    💳 {{ __('messages.pay') }}
                </a>

                <!-- نموذج إنشاء طلب -->
                <form method="GET" onsubmit="return redirectToCreateRequest(event)" class="inline-flex flex-wrap items-center gap-3">
                    <select id="request-type" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200" required>
                        <option value="">-- {{ __('messages.select_type') }} --</option>
                        <option value="resell">{{ __('messages.resell') }}</option>
                        <option value="change-plan">{{ __('messages.change_plan') }}</option>
                        <option value="change-chip">{{ __('messages.change_chip') }}</option>
                        <option value="pause">{{ __('messages.pause') }}</option>
                        <option value="resume">{{ __('messages.resume') }}</option>
                        <option value="change-date">{{ __('messages.change_date') }}</option>
                        <option value="change-distributor">{{ __('messages.change_distributor') }}</option>
                        <option value="stop-line">{{ __('messages.stop_line') }}</option>
                    </select>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 text-white font-black text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25 active:scale-95">
                        📦 {{ __('messages.create_request') }}
                    </button>
                </form>

                <!-- زر تحديث بيانات (العميل والملاحظات فقط) -->
                @if($line->customer)
                    <button @click="showUpdateModal = true" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-black text-sm transition-all shadow-lg shadow-amber-500/25 active:scale-95 cursor-pointer">
                        ✏️ تحديث بيانات العميل والملاحظات
                    </button>
                @endif

                <!-- زر تعديل (للخط بالكامل) -->
                <a href="{{ route('lines.edit', $line->id) }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-black text-sm border border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all shadow-sm active:scale-95">
                    ⚙️ تعديل كامل بيانات الخط
                </a>

                {{-- Update Customer Details Modal --}}
                @if($line->customer)
                    <div x-show="showUpdateModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" x-cloak>
                        <div class="relative w-full max-w-2xl bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 flex flex-col max-h-[90vh]" @click.away="showUpdateModal = false">
                            <!-- Header -->
                            <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700/50 p-6">
                                <h4 class="text-xl font-black text-gray-800 dark:text-white">✏️ تحديث بيانات العميل والملاحظات</h4>
                                <button @click="showUpdateModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-2xl font-black">&times;</button>
                            </div>
                            
                            <!-- Form -->
                            <form action="{{ route('lines.update-customer', $line) }}" method="POST" class="flex flex-col overflow-hidden">
                                @csrf
                                <!-- Scrollable body -->
                                <div class="p-6 overflow-y-auto space-y-4 max-h-[60vh]">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- الاسم -->
                                        <div class="col-span-1 md:col-span-2">
                                            <label class="block text-sm font-bold text-gray-600 dark:text-gray-400 mb-1">الاسم الكامل</label>
                                            <input type="text" name="full_name" value="{{ old('full_name', $line->customer->full_name) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                                        </div>
                                        <!-- الرقم القومي -->
                                        <div>
                                            <label class="block text-sm font-bold text-gray-600 dark:text-gray-400 mb-1">الرقم القومي (14 رقم)</label>
                                            <input type="text" name="national_id" value="{{ old('national_id', $line->customer->national_id) }}" maxlength="14" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                                        </div>
                                        <!-- تاريخ الميلاد -->
                                        <div>
                                            <label class="block text-sm font-bold text-gray-600 dark:text-gray-400 mb-1">تاريخ الميلاد</label>
                                            <input type="date" name="birth_date" value="{{ old('birth_date', $line->customer->birth_date) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                                        </div>
                                        <!-- العنوان -->
                                        <div class="col-span-1 md:col-span-2">
                                            <label class="block text-sm font-bold text-gray-600 dark:text-gray-400 mb-1">العنوان</label>
                                            <input type="text" name="address" value="{{ old('address', $line->customer->address) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                                        </div>
                                        <!-- رقم التواصل -->
                                        <div>
                                            <label class="block text-sm font-bold text-gray-600 dark:text-gray-400 mb-1">رقم تواصل العميل</label>
                                            <input type="text" name="contact_number" value="{{ old('contact_number', $line->customer->contact_number) }}" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                                        </div>
                                        <!-- واتساب -->
                                        <div>
                                            <label class="block text-sm font-bold text-gray-600 dark:text-gray-400 mb-1">رقم واتساب</label>
                                            <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $line->customer->whatsapp_number) }}" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                                        </div>
                                        <!-- ملاحظات -->
                                        <div class="col-span-1 md:col-span-2">
                                            <label class="block text-sm font-bold text-gray-600 dark:text-gray-400 mb-1">الملاحظات</label>
                                            <textarea name="notes" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">{{ old('notes', $line->notes) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer Actions -->
                                <div class="flex justify-end gap-3 p-6 border-t border-gray-100 dark:border-gray-700/50 bg-gray-50 dark:bg-gray-800/50 rounded-b-3xl">
                                    <button type="button" @click="showUpdateModal = false" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-xl text-sm transition active:scale-95 cursor-pointer">إلغاء</button>
                                    <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-sm transition active:scale-95 shadow-lg shadow-amber-500/25 cursor-pointer">حفظ التغييرات</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Requests Table with Toggle Button -->
            <div x-data="{ showRequests: false }" class="pt-6">
                <!-- Toggle Button - FIXED FOR JSON FILES -->
                <div class="mb-4 flex items-center gap-2">
                    <button 
                        @click="showRequests = !showRequests"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25 active:scale-95">
                        <span x-show="!showRequests">👁️ {{ __('show_requests') }}</span>
                        <span x-show="showRequests">👁️‍🗨️ {{ __('hide_requests') }}</span>
                    </button>
                    <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">
                        ({{ count($requests ?? []) }} {{ __('messages.requests') ?? 'طلبات' }})
                    </span>
                </div>

                <!-- Requests Table -->
                <div x-show="showRequests" x-transition class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">{{ __('messages.requests_for_line') ?? 'الطلبات المرتبطة بالخط' }}</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-center border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm">
                            <thead class="bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 text-sm font-semibold">
                                <tr>
                                    <th class="p-3">{{ __('messages.type') }}</th>
                                    <th class="p-3">{{ __('messages.status') }}</th>
                                    <th class="p-3">{{ __('messages.request_date') }}</th>
                                    <th class="p-3">{{ __('messages.requested_by') }}</th>
                                    <th class="p-3">{{ __('messages.notes') }}</th>
                                    <th class="p-3">{{ __('messages.details') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($requests ?? collect() as $req)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors border-b border-gray-100 dark:border-gray-700/50">
                                        <td class="p-3">{{ __('messages.request_type_'.$req->request_type) ?? $req->request_type }}</td>
                                        <td class="p-3">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold 
                                                @if($req->status == 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                                @elseif($req->status == 'inprogress') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                                @elseif($req->status == 'done') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                                @elseif($req->status == 'cancelled') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                                @endif">
                                                {{ __('messages.status_'.$req->status) ?? $req->status }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-gray-600 dark:text-gray-400 font-medium">{{ $req->created_at->format('Y-m-d') }}</td>
                                        <td class="p-3">{{ $req->requestedBy->name ?? '-' }}</td>
                                        <td class="p-3">{{ \Illuminate\Support\Str::limit($req->notes ?? ($req->resellDetails->comment ?? ''), 80) }}</td>
                                        <td class="p-3 flex items-center justify-center gap-2">
                                            <a href="{{ route('requests.show', $req->id) }}" class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline transition-all">{{ __('messages.view') }}</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-6 text-center text-gray-500 dark:text-gray-400">
                                            {{ __('messages.no_requests_found') ?? 'لا توجد طلبات مسجلة' }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $requests->links() ?? '' }}
                    </div>
                </div>
            </div>

            @if($line->customer)  
                <div class="pt-4">  
                    <a href="{{ route('customers.show', $line->customer) }}"  
                       class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-sm border border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all shadow-sm">  
                        🔙 {{ __('messages.back_to_customer_details') }}
                    </a>  
                </div>  
            @endif  
        </div>  
    </div>  
@push('scripts')
<script>
    function redirectToCreateRequest(event) {
        event.preventDefault();
        const type = document.getElementById('request-type').value;
        if (!type) {
            alert("❌ {{ __('messages.select_request_type_first') }}");
            return false;
        }
        const lineId = {{ $line->id }};
        const baseUrl = {
            'resell': '/admin/requests/resell/' + lineId,
            'change-plan': '/admin/requests/change-plan/' + lineId,
            'change-chip': '/admin/requests/change-chip/' + lineId,
            'pause': '/admin/requests/pause/' + lineId,
            'resume': '/admin/requests/resume/' + lineId + '/create',
            'change-date': '/admin/requests/change-date/' + lineId,
            'change-distributor': '/admin/requests/change-distributor/' + lineId,
            'stop-line': '/admin/requests/stop/' + lineId,
        };
        if (baseUrl[type]) {
            window.location.href = baseUrl[type];
        } else {
            alert("❌ {{ __('messages.request_type_not_supported') }}");
        }
    }
</script>
@endpush
</x-app-layout>