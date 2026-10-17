@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>
