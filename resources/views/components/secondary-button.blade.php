<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-outline-secondary inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-xl font-semibold text-sm text-gray-700 shadow-sm hover:bg-gray-50 active:bg-gray-100 hover:-translate-y-0.5 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 disabled:pointer-events-none']) }}>
    {{ $slot }}
</button>

