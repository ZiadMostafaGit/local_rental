<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Rent;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function show($id)
    {
        $customerId = 16; // استخدمي auth('customer')->id() في الحالة الفعلية
        $item = Item::findOrFail($id);

        // تحقق من وجود طلب "موافق عليه" لهذا العنصر
        $approvedRent = Rent::where('customer_id', $customerId)
        ->where('item_id', $item->id)
        ->where('rental_status', 'approved')
        ->whereNull('start_date')
        ->first();

    if ($approvedRent) {
        session()->flash('info', 'تمت الموافقة على طلب الإيجار. يرجى إكمال تفاصيل الإيجار.');
        session()->flash('approved_rent_id', $approvedRent->id);
    } else {
        $rejectedRent = Rent::where('customer_id', $customerId)
            ->where('item_id', $item->id)
            ->where('rental_status', 'rejected')
            ->orderBy('id', 'desc')
            ->first();

            if ($rejectedRent) {
                session()->flash('error', 'تم رفض طلب الإيجار لهذا العنصر.');
            } else {
                // نحذف أي رسائل فلاش قديمة
                session()->forget(['info', 'approved_rent_id', 'error']);
            }
        }

        return view('item_page', compact('item'));
    }
}
