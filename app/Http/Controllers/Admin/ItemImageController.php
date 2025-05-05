<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Item;
use App\Models\ItemImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;



class ItemImageController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
   
        public function index(Item $item)
        {
            $itemImages = $item->images;
            return view('admin_dashboard.pages.items.item_image', compact('item','itemImages'));
        }
    
    



    public function store(Request $request, Item $item)
{
    // التحقق من الصور
    $request->validate([
        'images' => 'required|array',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    foreach ($request->file('images') as $uploadedImage) {
        $itemImage = ItemImage::create([
            'item_id' => $item->id,
        ]);

        // تحديد اسم الملف بناءً على ID السجل
        $filename = $itemImage->id . '.png';

        // فتح الصورة باستخدام GD
        $img = imagecreatefromstring(file_get_contents($uploadedImage));

        // تحويل الصورة إلى PNG وحفظها
        imagepng($img, storage_path('app/public/item_images/' . $filename));

        // تحرير الذاكرة
        imagedestroy($img);
    }

    return redirect()->route('item.images.index', $item->id)
        ->with('success', 'Images uploaded successfully.');
}

}
