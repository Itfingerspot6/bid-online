@extends('layouts.app')

@section('title', 'Buat Lelang')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="font-display text-3xl text-white mb-2">Buat Lelang</h1>
    <p class="text-zinc-400 mb-8">Isi detail barang yang ingin kamu lelang</p>

    <form method="POST" action="{{ route('auctions.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Judul --}}
        <div>
            <label class="block text-sm text-zinc-400 mb-2">Judul Lelang</label>
            <input type="text" name="title" value="{{ old('title') }}"
                class="w-full bg-zinc-900 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-amber-400 transition-colors"
                placeholder="Contoh: iPhone 14 Pro Max 256GB">
            @error('title')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Kategori --}}
        <div>
            <label class="block text-sm text-zinc-400 mb-2">Kategori</label>
            <select name="category_id" class="w-full bg-zinc-900 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-amber-400 transition-colors">
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="block text-sm text-zinc-400 mb-2">Deskripsi</label>
            <textarea name="description" rows="4"
                class="w-full bg-zinc-900 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-amber-400 transition-colors resize-none"
                placeholder="Jelaskan kondisi dan detail barang...">{{ old('description') }}</textarea>
            @error('description')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Gambar --}}
        <div>
            <label class="block text-sm text-zinc-400 mb-2">Foto Barang</label>
            <input type="file" name="images[]" multiple accept="image/*"
                class="w-full bg-zinc-900 border border-zinc-700 rounded-xl px-4 py-3 text-zinc-400 focus:outline-none focus:border-amber-400 transition-colors file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-sm file:bg-amber-400 file:text-zinc-950 file:font-semibold hover:file:bg-amber-300">
            @error('images')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Harga --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-zinc-400 mb-2">Harga Awal (Rp)</label>
                <input type="number" name="start_price" value="{{ old('start_price') }}" min="0"
                    class="w-full bg-zinc-900 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-amber-400 transition-colors"
                    placeholder="0">
                @error('start_price')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm text-zinc-400 mb-2">Min. Kenaikan Bid (Rp)</label>
                <input type="number" name="min_bid_increment" value="{{ old('min_bid_increment', 1000) }}" min="0"
                    class="w-full bg-zinc-900 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-amber-400 transition-colors">
                @error('min_bid_increment')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Waktu --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-zinc-400 mb-2">Waktu Mulai</label>
                <input type="datetime-local" name="start_time" value="{{ old('start_time') }}"
                    class="w-full bg-zinc-900 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-amber-400 transition-colors">
                @error('start_time')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm text-zinc-400 mb-2">Waktu Selesai</label>
                <input type="datetime-local" name="end_time" value="{{ old('end_time') }}"
                    class="w-full bg-zinc-900 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-amber-400 transition-colors">
                @error('end_time')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
    <label class="block text-sm mb-2" style="color: #6B7280;">Harga Buy Now (Rp) <span class="text-xs opacity-60">- opsional, bid langsung menang jika mencapai harga ini</span></label>
    <input type="number" name="buy_now_price" value="{{ old('buy_now_price') }}" min="0"
        class="w-full rounded-xl px-4 py-3 focus:outline-none transition-colors"
        style="background-color: #F5F0E8; border: 2px solid #EDE7D9; color: #1B2A4A;"
        placeholder="Kosongkan jika tidak ada">
</div>
        {{-- Submit --}}
        <div class="flex gap-4 pt-2">
            <a href="{{ route('dashboard') }}" class="flex-1 text-center px-6 py-3 border border-zinc-700 text-zinc-300 rounded-xl hover:border-zinc-500 hover:text-white transition-all">
                Batal
            </a>
            <button type="submit" class="flex-1 px-6 py-3 bg-amber-400 text-zinc-950 font-semibold rounded-xl hover:bg-amber-300 transition-colors">
                Buat Lelang
            </button>
        </div>
    </form>
</div>
@endsection