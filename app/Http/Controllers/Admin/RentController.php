<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rents = DB::table('rents')
            ->join('items', 'rents.item_id', '=', 'items.id')
            ->join('customers', 'rents.customer_id', '=', 'customers.id')
            ->select(
                'rents.id',
                'items.title as item_name',
                'customers.first_name',
                'customers.last_name',
                'rents.start_date',
                'rents.end_date',
                'rents.delivery_address',
                'rents.payment_token',
                'rents.rental_status',

            )->get();

        return view('admin_dashboard.pages.rent.index', compact('rents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $customers = DB::table('customers')
            ->select('id', DB::raw("CONCAT(first_name, ' ', last_name) AS full_name"))
            ->get();
        $items = DB::table('items')
            ->select('id', 'title')
            ->get();
        return view('admin_dashboard.pages.rent.create', compact('customers', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'       => 'required|exists:customers,id',
            'item_id'           => 'required|exists:items,id',
            'rented_time'       => 'nullable|date',
            'start_date'        => 'nullable|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
            'delivery_address'  => 'nullable|string|max:100',
            'payment_token'     => 'nullable|string',
            'rental_status'     => 'required|in:pending,approved,rejected',
        ]);

        Rent::create([
            'customer_id'       => $validated['customer_id'],
            'item_id'           => $validated['item_id'],
            'rented_time'       => $validated['rented_time'],
            'start_date'        => $validated['start_date'],
            'end_date'          => $validated['end_date'],
            'delivery_address'  => $validated['delivery_address'],
            'payment_token'     => $validated['payment_token'],
            'rental_status'     => $validated['rental_status'],
        ]);

        return redirect()->route('rental.index')->with('success', 'تم إنشاء الطلب بنجاح');
    }

 
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rent = Rent::findOrFail($id);
        $customers = DB::table('customers')
            ->select('id', DB::raw("CONCAT(first_name, ' ', last_name) AS full_name"))
            ->get();
        $items = DB::table('items')
            ->select('id', 'title')
            ->get();
        return view('admin_dashboard.pages.rent.edit', compact('rent', 'customers', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $rent = Rent::findOrFail($id);

        $validated = $request->validate([
            'customer_id'       => 'required|exists:customers,id',
            'item_id'           => 'required|exists:items,id',
            'rented_time'       => 'nullable|date',
            'start_date'        => 'nullable|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
            'delivery_address'  => 'nullable|string|max:100',
            'payment_token'     => 'nullable|string',
            'rental_status'     => 'required|in:pending,approved,rejected',
        ]);

        $rent->update([
            'customer_id'       => $validated['customer_id'],
            'item_id'           => $validated['item_id'],
            'rented_time'       => $validated['rented_time'],
            'start_date'        => $validated['start_date'],
            'end_date'          => $validated['end_date'],
            'delivery_address'  => $validated['delivery_address'],
            'payment_token'     => $validated['payment_token'],
            'rental_status'     => $validated['rental_status'],
        ]);

        return redirect()->route('rental.index')->with('success', 'تم تحديث الطلب بنجاح');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Rent::findOrFail($id);
        $order->delete();

        return redirect()->route('rental.index')->with('success', 'تم حذف الطلب بنجاح');
    }
}
