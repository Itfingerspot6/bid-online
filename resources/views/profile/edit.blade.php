@extends('layouts.app')

@section('title', 'Pengaturan Profil')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    {{-- Header --}}
    <div class="mb-12">
        <h1 class="font-display text-5xl text-white mb-3 tracking-tight">Pengaturan <span class="text-amber-400">Profil</span></h1>
        <p class="text-zinc-500 text-lg">Kelola informasi pribadi, keamanan, dan preferensi akun Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        
        {{-- Left Sidebar: Quick Info --}}
        <div class="lg:col-span-4 space-y-8">
            <div class="glass-card p-8 rounded-[2.5rem] text-center relative overflow-hidden group">
                {{-- Decorative Backglow --}}
                <div class="absolute top-0 inset-x-0 h-32 bg-gradient-to-b from-amber-400/10 to-transparent -z-10"></div>
                
                <div class="relative inline-block mb-6">
                    <div class="w-24 h-24 rounded-[2rem] overflow-hidden border-2 border-amber-400/20 p-1 bg-zinc-900 shadow-2xl">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" class="w-full h-full object-cover rounded-[1.8rem]">
                        @else
                            <div class="w-full h-full bg-zinc-800 flex items-center justify-center text-zinc-600">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                        @endif
                    </div>
                </div>

                <h3 class="text-2xl font-bold text-white mb-1">{{ $user->name }}</h3>
                <p class="text-zinc-500 text-sm mb-6">{{ $user->email }}</p>

                <div class="grid grid-cols-2 gap-4 border-t border-white/5 pt-6">
                    <div class="text-left">
                        <p class="text-[10px] text-zinc-500 uppercase font-black tracking-widest mb-1">Saldo</p>
                        <p class="text-amber-400 font-bold">Rp {{ number_format($user->balance, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-left">
                        <p class="text-[10px] text-zinc-500 uppercase font-black tracking-widest mb-1">Status</p>
                        <p class="text-green-400 font-bold">Member</p>
                    </div>
                </div>
            </div>

            <div class="glass-card p-6 rounded-[2rem] space-y-2">
                <p class="text-[10px] text-zinc-500 uppercase font-black tracking-widest px-4 mb-4">Navigasi Pengaturan</p>
                <a href="#personal-info" class="flex items-center gap-4 p-4 bg-white/5 rounded-2xl text-white font-medium border border-white/5 hover:border-amber-400/30 transition-all group">
                    <span class="w-8 h-8 rounded-lg bg-amber-400/10 text-amber-400 flex items-center justify-center group-hover:scale-110 transition-transform">👤</span>
                    Informasi Pribadi
                </a>
                <a href="#security" class="flex items-center gap-4 p-4 hover:bg-white/5 rounded-2xl text-zinc-400 hover:text-white transition-all">
                    <span class="w-8 h-8 rounded-lg bg-zinc-800 flex items-center justify-center">🔐</span>
                    Keamanan
                </a>
                <a href="#danger-zone" class="flex items-center gap-4 p-4 hover:bg-red-500/5 rounded-2xl text-zinc-400 hover:text-red-400 transition-all">
                    <span class="w-8 h-8 rounded-lg bg-zinc-800 flex items-center justify-center">⚠️</span>
                    Zona Bahaya
                </a>
            </div>
        </div>

        {{-- Right Content: Forms --}}
        <div class="lg:col-span-8 space-y-12">
            
            {{-- Personal Info Section --}}
            <section id="personal-info" class="glass-card p-8 sm:p-12 rounded-[3rem]">
                @include('profile.partials.update-profile-information-form')
            </section>

            {{-- Security Section --}}
            <section id="security" class="glass-card p-8 sm:p-12 rounded-[3rem]">
                @include('profile.partials.update-password-form')
            </section>

            {{-- Danger Zone Section --}}
            <section id="danger-zone" class="glass-card p-8 sm:p-12 rounded-[3rem] border-red-500/10">
                @include('profile.partials.delete-user-form')
            </section>

        </div>
    </div>
</div>
@endsection
