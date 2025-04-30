<?php
namespace App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Controller;

use App\Models\Item;
use App\Models\ItemImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;



class ItemImageController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function index(Item $item)
    {

        $itemImages = $item->images;


        return Response::json([
            'success' => true,
            'images' => $itemImages->map(function ($image) {
                return [
                    'id' => $image->id,
                    'filename' => $image->id . '.png', // assuming you're using the ID as the filename
                    'url' => asset('storage/item_images/' . $image->id . '.png')
                ];
            })
        ]);
    }


    public function store(Request $request, Item $item)
    {
        // التحقق من الصور
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $savedImages = [];

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

            // إضافة الصورة المحفوظة إلى القائمة
            $savedImages[] = [
                'id' => $itemImage->id,
                'filename' => $filename,
                'url' => asset('storage/item_images/' . $filename)
            ];
        }

        // إرجاع الاستجابة بتنسيق JSON مع بيانات الصور المحفوظة
        return Response::json([
            'success' => true,
            'message' => 'Images uploaded successfully.',
            'images' => $savedImages
        ]);
    } 

}
