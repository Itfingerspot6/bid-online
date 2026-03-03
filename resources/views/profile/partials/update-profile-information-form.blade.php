<section>
    <header class="mb-8">
        <h2 class="text-2xl font-display text-white">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-2 text-sm text-zinc-500">
            {{ __("Perbarui informasi akun, bio, dan foto profil Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-8">
        @csrf
        @method('patch')

        {{-- Avatar Upload Section --}}
        <div class="flex flex-col sm:flex-row items-center gap-8 p-6 rounded-3xl bg-white/[0.02] border border-white/5">
            <div class="relative group">
                <div class="w-24 h-24 rounded-[2rem] overflow-hidden border-2 border-amber-400/20 p-1 bg-zinc-900 shadow-2xl">
                    <img id="avatar-preview" 
                         src="{{ $user->avatar ? Storage::url($user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=18181b&color=fbbf24' }}" 
                         class="w-full h-full object-cover rounded-[1.8rem]">
                </div>
                <label for="avatar-input" class="absolute -bottom-2 -right-2 w-10 h-10 bg-amber-400 hover:bg-amber-300 text-zinc-950 rounded-xl flex items-center justify-center cursor-pointer shadow-lg transition-transform group-hover:scale-110 active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </label>
                <input id="avatar-input" type="file" name="avatar" class="hidden" accept="image/*" onchange="previewAvatar(this)">
            </div>
            <div class="flex-1 text-center sm:text-left">
                <h4 class="text-white font-bold mb-1">Foto Profil</h4>
                <p class="text-xs text-zinc-500 mb-3">Format JPG, PNG atau WebP. Maksimal 2MB.</p>
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Name --}}
            <div class="space-y-2">
                <x-input-label for="name" :value="__('Nama Lengkap')" class="text-zinc-400 text-[10px] uppercase font-black tracking-widest ml-4" />
                <input id="name" name="name" type="text" class="w-full bg-zinc-800/50 border border-zinc-700/50 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-amber-400 transition-all" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            {{-- Email --}}
            <div class="space-y-2">
                <x-input-label for="email" :value="__('Alamat Email')" class="text-zinc-400 text-[10px] uppercase font-black tracking-widest ml-4" />
                <input id="email" name="email" type="email" class="w-full bg-zinc-800/50 border border-zinc-700/50 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-amber-400 transition-all" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="p-4 mt-4 rounded-2xl bg-amber-400/5 border border-amber-400/10">
                        <p class="text-sm text-zinc-400">
                            {{ __('Email Anda belum diverifikasi.') }}
                            <button form="send-verification" class="text-amber-400 hover:text-amber-300 font-bold ml-1 transition-colors">
                                {{ __('Kirim ulang email verifikasi.') }}
                            </button>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-xs font-bold text-green-400">
                                {{ __('Tautan verifikasi baru telah dikirim.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Location --}}
            <div class="space-y-2">
                <x-input-label for="location" :value="__('Lokasi')" class="text-zinc-400 text-[10px] uppercase font-black tracking-widest ml-4" />
                <input id="location" name="location" type="text" placeholder="Contoh: Jakarta, Indonesia" class="w-full bg-zinc-800/50 border border-zinc-700/50 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-amber-400 transition-all" value="{{ old('location', $user->location) }}" />
                <x-input-error class="mt-2" :messages="$errors->get('location')" />
            </div>
        </div>

        {{-- Bio --}}
        <div class="space-y-2">
            <x-input-label for="bio" :value="__('Biografi Singkat')" class="text-zinc-400 text-[10px] uppercase font-black tracking-widest ml-4" />
            <textarea id="bio" name="bio" rows="4" placeholder="Ceritakan sedikit tentang Anda..." class="w-full bg-zinc-800/50 border border-zinc-700/50 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-amber-400 transition-all resize-none">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div class="flex items-center gap-6 pt-4">
            <button type="submit" class="btn-primary px-12 py-4 text-sm uppercase font-black tracking-widest">
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-bold text-green-400"
                >✓ {{ __('Berhasil disimpan.') }}</p>
            @endif
        </div>
    </form>

    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</section>
