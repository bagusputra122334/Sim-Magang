@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'py-1.5 px-2.5 text-xs border-gray-300 dark:border-gray-700 dark:bg-slate-900 dark:text-slate-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) }}>
