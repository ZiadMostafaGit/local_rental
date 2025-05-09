<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::with('customer', 'item')
            ->select('reviews.*', DB::raw("CONCAT(customers.first_name, ' ', customers.last_name) AS customer_name"), 'items.title AS item_title')
            ->join('customers', 'reviews.customer_id', '=', 'customers.id')
            ->join('items', 'reviews.item_id', '=', 'items.id')
            ->get();
        return view('admin_dashboard.pages.reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = DB::table('customers')
            ->select('id', DB::raw("CONCAT(first_name, ' ', last_name) AS full_name"))
            ->get();
        $items = DB::table('items')
            ->select('id', 'title')
            ->get();
        return view('admin_dashboard.pages.reviews.create', compact('customers', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'item_id'     => 'required|exists:items,id',
            'rating'      => 'required|decimal:1,5',
            'comment'     => 'nullable|string|max:255',
        ]);

        DB::table('reviews')->insert($validated);

        return redirect()->route('reviews.index')->with('success', 'Review created successfully.');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $review = DB::table('reviews')->where('id', $id)->first();
        $customers = DB::table('customers')
            ->select('id', DB::raw("CONCAT(first_name, ' ', last_name) AS full_name"))
            ->get();
        $items = DB::table('items')
            ->select('id', 'title')
            ->get();

        return view('admin_dashboard.pages.reviews.edit', compact('review', 'customers', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'item_id'     => 'required|exists:items,id',
            'rating'      => 'required|decimal:1,5',
            'comment'     => 'nullable|string|max:255',
        ]);

        DB::table('reviews')->where('id', $id)->update($validated);

        return redirect()->route('reviews.index')->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::table('reviews')->where('id', $id)->delete();

        return redirect()->route('reviews.index')->with('success', 'Review deleted successfully.');
    }
}
