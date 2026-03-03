<section class="space-y-6">
    <header>
        <h2 class="text-2xl font-display text-white">
            {{ __('Hapus Akun') }}
        </h2>

        <p class="mt-2 text-sm text-zinc-500">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.') }}
        </p>
    </header>

    <button
        class="px-8 py-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all shadow-lg hover:shadow-red-500/20"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Hapus Akun Permanen') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <div class="glass-card p-0 rounded-[2.5rem] overflow-hidden border-white/10">
            <form method="post" action="{{ route('profile.destroy') }}" class="p-8 sm:p-12 bg-zinc-900/90 backdrop-blur-2xl">
                @csrf
                @method('delete')

                <h2 class="text-2xl font-display text-white">
                    {{ __('Apakah Anda yakin ingin menghapus akun?') }}
                </h2>

                <p class="mt-4 text-sm text-zinc-500">
                    {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Silakan masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}
                </p>

                <div class="mt-8 space-y-2">
                    <x-input-label for="password" value="{{ __('Kata Sandi') }}" class="text-zinc-400 text-[10px] uppercase font-black tracking-widest ml-4" />
                    <input id="password" name="password" type="password" class="w-full bg-zinc-800/50 border border-zinc-700/50 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-red-500 transition-all" placeholder="{{ __('Kata Sandi') }}" />
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="mt-10 flex justify-end gap-4">
                    <button type="button" class="px-8 py-4 bg-white/5 border border-white/10 text-zinc-400 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-white/10 hover:text-white transition-all" x-on:click="$dispatch('close')">
                        {{ __('Batal') }}
                    </button>

                    <button type="submit" class="px-8 py-4 bg-red-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-red-500 transition-all shadow-lg shadow-red-600/20">
                        {{ __('Hapus Akun') }}
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</section>
