<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Lender;
use Illuminate\Http\Request;

class LenderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lenders = Lender::all();
        return view('admin_dashboard.pages.items.lenders.index', compact('lenders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    // عرض كل العناصر المعلقة للمراجعة
    public function pendingItems()
    {
        $items = Item::where('item_status', 'pending')->with('lender')->get();

        return  view('admin_dashboard.pages.items.lenders.pending', compact('items'));
    }

    // الموافقة على عنصر
    public function approve($id)
    {
        $item = Item::find($id);
    
        if (!$item) {
            return redirect()->back()->with('error', 'العنصر غير موجود.');
        }
    
        if ($item->item_status !== 'pending') {
            return redirect()->back()->with('error', 'العنصر ليس قيد المراجعة.');
        }
    
        $item->item_status = 'available';
        $item->save();
    
        return redirect()->back()->with('success', 'تمت الموافقة على العنصر بنجاح.');
    }
    public function reject($id)
    {
        $item = Item::find($id);
    
        if (!$item) {
            return redirect()->back()->with('error', 'العنصر غير موجود.');
        }
    
        if ($item->item_status !== 'pending') {
            return redirect()->back()->with('error', 'العنصر ليس قيد المراجعة.');
        }
    
        $item->item_status = 'unavailable';
        $item->save();
    
        return redirect()->back()->with('success', 'تم رفض العنصر.');
    }
        
}
