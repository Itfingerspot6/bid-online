@extends('layouts.app')

@section('title', 'Edit Lelang')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="font-display text-3xl text-white mb-2">Edit Lelang</h1>
    <p class="text-zinc-400 mb-8">Update detail lelang kamu</p>

    <form method="POST" action="{{ route('auctions.update', $auction) }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Judul --}}
        <div>
            <label class="block text-sm text-zinc-400 mb-2">Judul Lelang</label>
            <input type="text" name="title" value="{{ old('title', $auction->title) }}"
                class="w-full bg-zinc-900 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-amber-400 transition-colors">
            @error('title')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Kategori --}}
        <div>
            <label class="block text-sm text-zinc-400 mb-2">Kategori</label>
            <select name="category_id" class="w-full bg-zinc-900 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-amber-400 transition-colors">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $auction->category_id) == $category->id ? 'selected' : '' }}>
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
                class="w-full bg-zinc-900 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-amber-400 transition-colors resize-none">{{ old('description', $auction->description) }}</textarea>
            @error('description')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Min Bid --}}
        <div>
            <label class="block text-sm text-zinc-400 mb-2">Min. Kenaikan Bid (Rp)</label>
            <input type="number" name="min_bid_increment" value="{{ old('min_bid_increment', $auction->min_bid_increment) }}" min="0"
                class="w-full bg-zinc-900 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-amber-400 transition-colors">
            @error('min_bid_increment')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Waktu --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-zinc-400 mb-2">Waktu Mulai</label>
                <input type="datetime-local" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($auction->start_time)->format('Y-m-d\TH:i')) }}"
                    class="w-full bg-zinc-900 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-amber-400 transition-colors">
                @error('start_time')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm text-zinc-400 mb-2">Waktu Selesai</label>
                <input type="datetime-local" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($auction->end_time)->format('Y-m-d\TH:i')) }}"
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
            <a href="{{ route('auctions.show', $auction->slug) }}" class="flex-1 text-center px-6 py-3 border border-zinc-700 text-zinc-300 rounded-xl hover:border-zinc-500 hover:text-white transition-all">
                Batal
            </a>
            <button type="submit" class="flex-1 px-6 py-3 bg-amber-400 text-zinc-950 font-semibold rounded-xl hover:bg-amber-300 transition-colors">
                Update Lelang
            </button>
        </div>
    </form>
</div>
@endsection