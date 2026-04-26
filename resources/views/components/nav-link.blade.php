@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold leading-5 text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/40 focus:outline-none transition duration-150 ease-in-out shadow-sm'
            : 'inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold leading-5 text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
