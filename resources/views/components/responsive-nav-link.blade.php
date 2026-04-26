@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full px-4 py-3 rounded-2xl text-start text-base font-black text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/40 transition duration-150 ease-in-out'
            : 'block w-full px-4 py-3 rounded-2xl text-start text-base font-bold text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
