<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-display font-bold text-white tracking-tight">Selamat Datang</h2>
        <p class="text-zinc-400 text-sm mt-2">Masuk ke akun BidOnline Anda</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-black uppercase tracking-widest text-zinc-500 mb-2 ms-1">Email</label>
            <input id="email" 
                class="block w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-all" 
                type="email" 
                name="email" 
                placeholder="email@contoh.com"
                :value="old('email')" 
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-2 ms-1">
                <label for="password" class="block text-xs font-black uppercase tracking-widest text-zinc-500">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-[10px] font-black uppercase tracking-tighter text-amber-500/80 hover:text-amber-400 transition-colors" href="{{ route('password.request') }}">
                        Lupa?
                    </a>
                @endif
            </div>

            <input id="password" 
                class="block w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-all" 
                type="password"
                name="password"
                placeholder="••••••••"
                required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded-lg border-white/10 bg-white/5 text-amber-500 shadow-sm focus:ring-amber-400/50 focus:ring-offset-0 w-5 h-5 transition-all" name="remember">
                <span class="ms-3 text-sm text-zinc-400 group-hover:text-zinc-200 transition-colors">Ingat Saya</span>
            </label>
        </div>

        <button type="submit" class="w-full btn-primary !py-4 rounded-2xl font-black text-sm uppercase tracking-[0.2em] shadow-[0_10px_30px_-10px_rgba(245,158,11,0.4)] hover:shadow-amber-500/40 transition-all duration-300 transform active:scale-[0.98]">
            Log In
        </button>

        <div class="text-center pt-4">
            <p class="text-sm text-zinc-500">Belum punya akun? 
                <a href="{{ route('register') }}" class="text-amber-400 font-bold hover:text-amber-300 transition-colors ms-1">Daftar Sekarang</a>
            </p>
        </div>
    </form>
</x-guest-layout>
