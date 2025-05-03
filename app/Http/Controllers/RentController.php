<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Charge;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class RentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function rentrequest(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
        ]);

        $customerId = 16;

        // Create rental request with "pending" status
        Rent::create([
            'customer_id' => $customerId,
            'item_id' => $request->item_id,
            'rental_status' => 'pending', // Pending approval
        ]);

        return redirect()->back()->with('success', 'Rental request sent successfully.');
    }
    public function approveRequest($id)
    {
        $rent = Rent::findOrFail($id);
        $rent->rental_status = 'approved';
        $rent->save();

        return redirect()->route('lender.requests')->with('success', 'Request approved.');
    }

    public function rejectRequest($id)
    {
        $rent = Rent::findOrFail($id);
        $rent->rental_status = 'rejected';
        $rent->save();

        return redirect()->route('lender.requests')->with('error', 'Request rejected.');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // احصل على طلب الإيجار بناءً على rent_id
        $rent = Rent::findOrFail($request->rent_id);

        // احصل على العنصر المرتبط بالطلب
        $item = $rent->item;

        // تأكد من وجود الـ item
        if (!$item) {
            return redirect()->route('rent.form')->withErrors(['error' => 'Item not found']);
        }

        // تمرير الـ item إلى الـ view
        return view('rent.form', compact('item', 'rent'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'delivery_address' => 'required|string|max:100',
            'rent_id' => 'required|exists:rents,id', // تأكد أن الطلب موجود
        ]);

        $rent = Rent::findOrFail($request->rent_id);

        $item = $rent->item;
        $customer = $rent->customer;

        if (!$item || !$customer) {
            return redirect()->route('rent.form', ['rent_id' => $rent->id])->withErrors(['error' => 'Item or Customer not found']);
        }

        // تحقق من التداخل مع حجوزات أخرى
        $existingRent = Rent::where('item_id', $item->id)
            ->where('id', '!=', $rent->id) // تجاهل نفس الطلب
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
            })
            ->exists();

        if ($existingRent) {
            return redirect()->route('rent.form', ['rent_id' => $rent->id])->withErrors(['error' => 'Item is unavailable during the selected period']);
        }

        // تحديث بيانات الإيجار المعتمد
        $rent->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'delivery_address' => $request->delivery_address,
            'rental_status' => 'in_progress', // أو أي حالة تريدها بعد إدخال البيانات
        ]);

        // تحديث حالة العنصر
        $item->update(['item_status' => 'unavailable']);

        return view('rent.payment', [
            'item' => $item,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'delivery_address' => $request->delivery_address,
            'rent' => $rent,
        ]);
    }



    private function calculateAmount(Rent $rental)
    {
        $item = $rental->item;

        $rentalDays = (new \DateTime($rental->start_date))->diff(new \DateTime($rental->end_date))->days;

        return $item->price * $rentalDays * 100; // المبلغ بالـ cents (Stripe يتعامل مع الـ cents)
    }

    public function session(Request $request)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $customer = Customer::where('email', 'alaa@mail.com')->first();
            if (!$customer) {
                return response()->json(['error' => 'Customer not found'], 404);
            }

            $item = Item::find($request->item_id);
            if (!$item) {
                return response()->json(['error' => 'Item not found'], 404);
            }

            $rent = Rent::find($request->rental_id);
            if (!$rent) {
                return response()->json(['error' => 'Rent not found'], 404);
            }

            $amount = $this->calculateAmount($rent);

            $stripeCustomer = \Stripe\Customer::create([
                'email' => $customer->email,
                'name' => $customer->name,
                'phone' => $customer->phone,
            ]);

            $lineItems = [
                [
                    'price_data' => [
                        'currency' => 'egp',
                        'product_data' => [
                            'name' => 'Rental - ' . $item->name,
                        ],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => 1,
                ]
            ];

            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'customer' => $stripeCustomer->id,
                'success_url' => route('callback', ['id' => $rent->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('error'),
                'metadata' => [
                    'item_id' => $request->item_id,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'delivery_address' => $request->delivery_address,
                    'email' => $customer->email,
                    'rental_id' => $rent->id,
                ],
            ]);

            $rent->payment_token = $checkout_session->id;
            $rent->save();

            return response()->json(['id' => $checkout_session->id]);
        } catch (\Exception $e) {
            Log::debug('Error in Stripe session creation: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    // صفحة النجاح
    public function callback($id, Request $request)
    {


        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        // استرجاع الـ session بناءً على session_id
        $session = \Stripe\Checkout\Session::retrieve($request->get('session_id'));

        // إنشاء الإيجار باستخدام المعاملات المحفوظة في الـ metadata
        $rental = Rent::find($id);
        if ($rental) {
            $rental->payment_token = $session->id;
            $rental->save();
            // زيادة score للعميل بمقدار 10
            $customer = $rental->customer;
            if ($customer) {
                $customer->incrementScore(); // أو $customer->increment('score', 10);
            }
        } else {
            return redirect()->route('home')->with('error', 'Rental not found.');
        }

        return view('rent.success', compact('rental'))->with('score', $customer->score ?? 0);
    }




    // صفحة الخطأ
    public function error()
    {
        return view('rent.error');
    }
    /**
     * Display the specified resource.
     */
    public function show(Rent $rent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rent $rent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rent $rent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rent $rent)
    {
        //
    }
}
