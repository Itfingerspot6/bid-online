<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuctionController extends Controller
{
public function index(Request $request)
{
    $query = Auction::with(['seller', 'category'])
        ->where('status', 'active');

    if ($request->filled('q')) {
        $query->where('title', 'like', '%' . $request->q . '%');
    }

    if ($request->filled('category')) {
        $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
    }

    if ($request->filled('min_price')) {
        $query->where('current_price', '>=', $request->min_price);
    }

    if ($request->filled('max_price')) {
        $query->where('current_price', '<=', $request->max_price);
    }

    if ($request->sort === 'price_low') {
        $query->orderBy('current_price', 'asc');
    } elseif ($request->sort === 'price_high') {
        $query->orderBy('current_price', 'desc');
    } elseif ($request->sort === 'newest') {
        $query->orderBy('created_at', 'desc');
    } else {
        $query->orderBy('end_time', 'asc');
    }

    $auctions = $query->paginate(12)->withQueryString();
    $categories = Category::all();

    return view('auctions.index', compact('auctions', 'categories'));
}

    public function landing()
{
    $latestAuctions = Auction::with(['category'])
        ->where('status', 'active')
        ->latest()
        ->take(4)
        ->get();

    return view('landing', compact('latestAuctions'));
}

    public function create()
    {
        $categories = Category::all();
        return view('auctions.create', compact('categories'));
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'title'             => 'required|string|max:255',
        'category_id'       => 'required|exists:categories,id',
        'description'       => 'required|string',
        'images'            => 'nullable|array',
        'images.*'          => 'image|max:2048',
        'start_price'       => 'required|numeric|min:0',
        'min_bid_increment' => 'required|numeric|min:0',
        'buy_now_price'     => 'nullable|numeric|min:0',
        'start_time'        => 'required|date|after:now',
        'end_time'          => 'required|date|after:start_time',
    ]);

    $images = [];
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $images[] = $image->store('auctions', 'public');
        }
    }

    Auction::create([
        ...$validated,
        'user_id'       => auth()->id(),
        'slug'          => Str::slug($validated['title']) . '-' . Str::random(6),
        'current_price' => $validated['start_price'],
        'images'        => $images,
        'status'        => 'draft',
    ]);

    return redirect()->route('home')->with('success', 'Lelang berhasil dibuat!');
}

public function update(Request $request, Auction $auction)
{
    $this->authorize('update', $auction);

    $validated = $request->validate([
        'title'             => 'required|string|max:255',
        'category_id'       => 'required|exists:categories,id',
        'description'       => 'required|string',
        'min_bid_increment' => 'required|numeric|min:0',
        'buy_now_price'     => 'nullable|numeric|min:0',
        'start_time'        => 'required|date',
        'end_time'          => 'required|date|after:start_time',
    ]);

    $auction->update($validated);

    return redirect()->route('auctions.show', $auction->slug)->with('success', 'Lelang berhasil diupdate!');
}
    public function show($slug)
    {
        $auction = Auction::with(['seller', 'category', 'bids.user', 'highestBid'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('auctions.show', compact('auction'));
    }

    public function edit(Auction $auction)
    {
        $this->authorize('update', $auction);
        $categories = Category::all();
        return view('auctions.edit', compact('auction', 'categories'));
    }


    public function destroy(Auction $auction)
    {
        $this->authorize('delete', $auction);
        $auction->update(['status' => 'cancelled']);
        return redirect()->route('home')->with('success', 'Lelang dibatalkan.');
    }

    public function updateStatus(Request $request, Auction $auction)
    {
        $request->validate(['status' => 'required|in:draft,active,closed,cancelled']);
        $auction->update(['status' => $request->status]);
        return back()->with('success', 'Status lelang diupdate!');
    }
}