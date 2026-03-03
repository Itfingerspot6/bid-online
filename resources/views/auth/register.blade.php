<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-display font-bold text-white tracking-tight">Buat Akun</h2>
        <p class="text-zinc-400 text-sm mt-2">Bergabung dengan komunitas lelang terbaik</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-black uppercase tracking-widest text-zinc-500 mb-2 ms-1">Nama Lengkap</label>
            <input id="name" 
                class="block w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-all" 
                type="text" 
                name="name" 
                placeholder="Nama Anda"
                :value="old('name')" 
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-black uppercase tracking-widest text-zinc-500 mb-2 ms-1">Email</label>
            <input id="email" 
                class="block w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-all" 
                type="email" 
                name="email" 
                placeholder="email@contoh.com"
                :value="old('email')" 
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-black uppercase tracking-widest text-zinc-500 mb-2 ms-1">Password</label>
            <input id="password" 
                class="block w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-all" 
                type="password"
                name="password"
                placeholder="••••••••"
                required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-black uppercase tracking-widest text-zinc-500 mb-2 ms-1">Konfirmasi Password</label>
            <input id="password_confirmation" 
                class="block w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 transition-all" 
                type="password"
                name="password_confirmation" 
                placeholder="••••••••"
                required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="w-full btn-primary !py-4 rounded-2xl font-black text-sm uppercase tracking-[0.2em] shadow-[0_10px_30px_-10px_rgba(245,158,11,0.4)] hover:shadow-amber-500/40 transition-all duration-300 transform active:scale-[0.98] mt-4">
            Daftar Akun
        </button>

        <div class="text-center pt-4">
            <p class="text-sm text-zinc-500">Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-amber-400 font-bold hover:text-amber-300 transition-colors ms-1">Log In</a>
            </p>
        </div>
    </form>
</x-guest-layout>
