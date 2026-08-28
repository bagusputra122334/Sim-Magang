<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white !text-white border border-transparent rounded-xl font-semibold text-sm shadow-sm hover:-translate-y-0.5 hover:shadow-md hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 disabled:pointer-events-none']) }}>
    {{ $slot }}
</button>


