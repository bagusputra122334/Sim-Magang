@extends('layouts.auth')

@section('content')
    {{ $slot }}
@endsection

@section('auth-footer')
    @php
        $currentRoute = request()->route()?->getName() ?? '';
    @endphp
    @if($currentRoute === 'login' && Route::has('register'))
        <span>Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 hover:underline transition-colors">Daftar Sekarang</a></span>
    @elseif($currentRoute === 'register' && Route::has('login'))
        <span>Sudah terdaftar? <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 hover:underline transition-colors">Sign In</a></span>
    @elseif(in_array($currentRoute, ['password.request', 'password.email']) && Route::has('login'))
        <span>Ingat kredensial? <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 hover:underline transition-colors">Kembali ke Login</a></span>
    @elseif(str_starts_with($currentRoute, 'password.') && Route::has('login'))
        <span><a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 hover:underline transition-colors">&larr; Kembali ke halaman Login</a></span>
    @elseif(str_starts_with($currentRoute, 'verification.') && Route::has('login'))
        <span><a href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('guest-logout-form').submit();" class="font-semibold text-indigo-600 hover:text-indigo-800 hover:underline transition-colors">Logout</a>
        </span>
        <form id="guest-logout-form" method="POST" action="{{ route('logout') }}" class="d-none">@csrf</form>
    @endif
@endsection

