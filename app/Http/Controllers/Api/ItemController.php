<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Rent;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function show($id)
    {
        $customer = auth('customer')->user();

        if (!$customer) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $item = Item::find($id);

        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        // تحقق من وجود طلب إيجار "موافق عليه" لم يُستكمل بعد
        $approvedRent = Rent::where('customer_id', $customer->id)
            ->where('item_id', $item->id)
            ->where('rental_status', 'approved')
            ->whereNull('start_date')
            ->first();

        if ($approvedRent) {
            return response()->json([
                'status' => 'approved_pending_details',
                'message' => 'تمت الموافقة على طلب الإيجار. يرجى إكمال تفاصيل الإيجار.',
                'approved_rent_id' => $approvedRent->id,
                'item' => $item
            ]);
        }

        // تحقق من وجود طلب مرفوض
        $rejectedRent = Rent::where('customer_id', $customer->id)
            ->where('item_id', $item->id)
            ->where('rental_status', 'rejected')
            ->orderBy('id', 'desc')
            ->first();

        if ($rejectedRent) {
            return response()->json([
                'status' => 'rejected',
                'message' => 'تم رفض طلب الإيجار لهذا العنصر.',
                'item' => $item
            ]);
        }

        return response()->json([
            'status' => 'available',
            'item' => $item
        ]);
    }
}
