<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\ItemImage;
use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('categories');

        if ($request->has('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('id', $request->input('category_id'));
            });
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        $items = $query->paginate(10);

        return response()->json($items);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        /** @var \App\Models\Lender $lender */
        $lender = auth('lender')->user();

        $item = new Item();
        $item->lender_id = $lender->id;
        $item->title = $validated['title'];
        $item->description = $validated['description'] ?? null;
        $item->price = $validated['price'];
        $item->item_status = 'pending';
        $item->save();

        // ربط الفئات بالعنصر
        $item->categories()->sync($validated['category_ids']);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $uploadedImage) {
                $itemImage = ItemImage::create([
                    'item_id' => $item->id,
                ]);

                $filename = $itemImage->id . '.png';
                $img = imagecreatefromstring(file_get_contents($uploadedImage));
                imagepng($img, storage_path('app/public/item_images/' . $filename));
                imagedestroy($img);
            }
        }

        $lender->increment('score', 10);

        return response()->json([
            'message' => 'Item added successfully and pending approval.',
            'item' => $item,
            'score' => $lender->score
        ]);
    }

    public function show($id)
    {
        $item = Item::with('categories')->find($id);

        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        $imagePaths = ItemImage::where('item_id', $item->id)
            ->pluck('id')
            ->map(function ($id) {
                return asset("storage/item_images/{$id}.png");
            });

        return response()->json([
            'status' => 'available',
            'item' => $item,
            'categories' => $item->categories,
            'images' => $imagePaths,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $item = Item::find($id);

        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        $item->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
        ]);

        // تحديث الفئات
        $item->categories()->sync($validated['category_ids']);

        // التعامل مع الصور إذا كانت موجودة
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $uploadedImage) {
                $itemImage = ItemImage::create([
                    'item_id' => $item->id,
                ]);

                $filename = $itemImage->id . '.png';
                $img = imagecreatefromstring(file_get_contents($uploadedImage));
                imagepng($img, storage_path('app/public/item_images/' . $filename));
                imagedestroy($img);
            }
        }

        return response()->json([
            'message' => 'Item updated successfully.',
            'item' => $item,
        ]);
    }

    public function destroy($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        if ($item->item_status === 'pending') {
            return response()->json(['error' => 'Cannot delete item while pending approval.'], 403);
        }

        $images = ItemImage::where('item_id', $item->id)->get();
        foreach ($images as $image) {
            Storage::delete('public/item_images/' . $image->id . '.png');
            $image->delete();
        }

        $item->delete();

        return response()->json(['message' => 'Item deleted successfully']);
    }
}
