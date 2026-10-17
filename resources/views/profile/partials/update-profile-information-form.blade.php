<section>
    <header class="mb-3">
        <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-2.5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-2 bg-amber-50 dark:bg-amber-950/30 rounded border border-amber-200 dark:border-amber-800/40">
                    <p class="text-xs text-amber-800 dark:text-amber-300">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-xs font-semibold text-amber-900 dark:text-amber-200 hover:text-amber-700 rounded focus:outline-none focus:ring-1 focus:ring-amber-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 font-medium text-xs text-emerald-600 dark:text-emerald-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        @if($user->isAdmin())
            <div>
                <x-input-label for="nip" :value="__('NIP (Nomor Induk Pegawai)')" />
                <x-text-input id="nip" name="nip" type="text" class="block w-full" :value="old('nip', $user->nip)" placeholder="Contoh: 19850722 201001 1 004" />
                <x-input-error class="mt-1" :messages="$errors->get('nip')" />
            </div>

            <div>
                <x-input-label for="position_title" :value="__('Jabatan / Subtansi Pengelola')" />
                <x-text-input id="position_title" name="position_title" type="text" class="block w-full" :value="old('position_title', $user->position_title)" placeholder="Contoh: Kepala Bidang SPBE / Penanggung Jawab Admin" />
                <x-input-error class="mt-1" :messages="$errors->get('position_title')" />
            </div>
        @endif

        <div class="flex items-center gap-3 pt-1">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs font-medium text-emerald-600 dark:text-emerald-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
