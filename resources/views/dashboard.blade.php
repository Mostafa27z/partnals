<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight text-center" data-aos="fade-down">
            {{ __('لوحة التحكم') }}
        </h2>
    </x-slot>

    <div class="py-12 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- بطاقة الترحيب --}}
            <div class="bg-[rgba(255,255,255,0.1)]  backdrop-blur-md rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
                <div class="p-8">
                    <h3 class="text-xl sm:text-2xl font-extrabold text-gray-800 dark:text-white mb-3">
                        👋 {{ __("أهلاً بك في لوحة التحكم!") }}
                    </h3>
                    <p class="text-gray-700 dark:text-gray-300 text-base leading-relaxed">
                        {{ __("هنا يمكنك متابعة وإدارة كل جوانب النظام بسهولة، بما في ذلك المستخدمين، الطلبات، الفواتير، والصلاحيات.") }}
                    </p>
                </div>
                <div class="bg-gray-100/60 dark:bg-gray-700/60 backdrop-blur-sm px-8 py-4 text-sm text-gray-700 dark:text-gray-300 border-t border-gray-200/50 dark:border-gray-600/50">
                    {{ __('💡 نصيحة: استخدم القائمة الجانبية للتنقل بين أقسام النظام.') }}
                </div>
            </div>

            {{-- أقسام سريعة --}}
            <div class="grid md:grid-cols-3 gap-6 mt-8">
                <div class="bg-blue-50/70 dark:bg-blue-900/40 backdrop-blur-md rounded-xl p-6 shadow hover:shadow-lg transition-all cursor-pointer" data-aos="zoom-in" data-aos-delay="150">
                    <h4 class="text-lg font-semibold text-blue-700 dark:text-blue-300 mb-2">📋 {{ __('العملاء') }}</h4>
                    <p class="text-gray-700 dark:text-gray-300 text-sm">{{ __('إدارة بيانات العملاء ومعلوماتهم.') }}</p>
                </div>

                <div class="bg-green-50/70 dark:bg-green-900/40 backdrop-blur-md rounded-xl p-6 shadow hover:shadow-lg transition-all cursor-pointer" data-aos="zoom-in" data-aos-delay="200">
                    <h4 class="text-lg font-semibold text-green-700 dark:text-green-300 mb-2">📦 {{ __('الطلبات') }}</h4>
                    <p class="text-gray-700 dark:text-gray-300 text-sm">{{ __('متابعة الطلبات وحالتها بسهولة.') }}</p>
                </div>

                <div class="bg-yellow-50/70 dark:bg-yellow-900/40 backdrop-blur-md rounded-xl p-6 shadow hover:shadow-lg transition-all cursor-pointer" data-aos="zoom-in" data-aos-delay="250">
                    <h4 class="text-lg font-semibold text-yellow-700 dark:text-yellow-300 mb-2">💰 {{ __('الفواتير') }}</h4>
                    <p class="text-gray-700 dark:text-gray-300 text-sm">{{ __('إدارة وإصدار الفواتير للعملاء.') }}</p>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
        <script>
            AOS.init({
                duration: 600,
                once: true
            });
        </script>
    @endpush
</x-app-layout>
