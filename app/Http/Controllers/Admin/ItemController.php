<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::with('categories')->get();
        $categories= Category::all();
        return view('admin_dashboard.pages.items.index', compact('items','categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin_dashboard.pages.items.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        $item = Item::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'item_status' => $request->item_status,
            'lender_id' => auth()->guard('lender')->id(),
        ]);

        $item->categories()->attach($request->category_id);


        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Item::with('categories')->findOrFail($id);
    $categories = Category::all();

    return view('admin_dashboard.pages.items.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        $item = Item::findOrFail($id);
        $item->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,

        ])->categories()->sync($request->category_id);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index');
    }

    public function addCategory(Request $request, Item $item)
{
    $request->validate([
        'category_id' => 'required|exists:categories,id'
    ]);

    if (!$item->categories->contains($request->category_id)) {
        $item->categories()->attach($request->category_id);
    }

    return back()->with('success', 'Item added to category.');
}

}
