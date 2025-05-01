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
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // احصل على الـ item الذي id = 3
        $item = Item::find(3);

        // تأكد من وجود الـ item
        if (!$item) {
            return redirect()->route('rent.form')->withErrors(['error' => 'Item not found']);
        }

        // تمرير الـ item إلى الـ view
        return view('rent.form', compact('item'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $customerid = 6;
        $itemid = 4;

        $customer = Customer::find($customerid);
        $item = Item::find($itemid);

        if (!$customer || !$item) {
            return redirect()->route('rent.form')->withErrors(['error' => 'Customer or Item not found']);
        }

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'delivery_address' => 'required|string|max:100',
        ]);

        $rent = Rent::create([
            'customer_id' => $customerid,
            'item_id' => $itemid,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'delivery_address' => $request->delivery_address,
        ]);

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
        } else {
            return redirect()->route('home')->with('error', 'Rental not found.');
        }

        return view('rent.success', compact('rental'));
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
