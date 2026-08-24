@extends('layouts.auth')

@section('content')
    {{ $slot }}
@endsection

@section('auth-footer')
    @php
        $currentRoute = request()->route()?->getName() ?? '';
    @endphp
    @if($currentRoute === 'login' && Route::has('register'))
        <span>Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a></span>
    @elseif($currentRoute === 'register' && Route::has('login'))
        <span>Sudah terdaftar? <a href="{{ route('login') }}">Sign In</a></span>
    @elseif(in_array($currentRoute, ['password.request', 'password.email']) && Route::has('login'))
        <span>Ingat kredensial? <a href="{{ route('login') }}">Kembali ke Login</a></span>
    @elseif(str_starts_with($currentRoute, 'password.') && Route::has('login'))
        <span><a href="{{ route('login') }}">&larr; Kembali ke halaman Login</a></span>
    @elseif(str_starts_with($currentRoute, 'verification.') && Route::has('login'))
        <span><a href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('guest-logout-form').submit();">Logout</a>
        </span>
        <form id="guest-logout-form" method="POST" action="{{ route('logout') }}" class="d-none">@csrf</form>
    @endif
@endsection
