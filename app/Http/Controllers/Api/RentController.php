<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class RentController extends Controller
{
    // إنشاء إيجار جديد
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'delivery_address' => 'required|string|max:100',
        ]);

        $customer = auth('customer')->user();
        if (!$customer) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $rent = Rent::create([
            'customer_id' => $customer->id,
            'item_id' => $request->item_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'delivery_address' => $request->delivery_address,
        ]);

        return response()->json([
            'message' => 'Rent created successfully',
            'rent' => $rent,
        ], 201);
    }

    // حساب المبلغ المستحق
    private function calculateAmount(Rent $rental)
    {
        $item = $rental->item;
        $rentalDays = (new \DateTime($rental->start_date))->diff(new \DateTime($rental->end_date))->days;

        return $item->price * $rentalDays * 100; // Stripe requires amount in cents
    }

    // إنشاء جلسة Stripe
    public function session(Request $request)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $request->validate([
                'item_id' => 'required|exists:items,id',
                'rental_id' => 'required|exists:rents,id',
            ]);

            $customer = auth('customer')->user();
            if (!$customer) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $item = Item::find($request->item_id);
            $rent = Rent::find($request->rental_id);

            $amount = $this->calculateAmount($rent);

            $stripeCustomer = \Stripe\Customer::create([
                'email' => $customer->email,
                'name' => $customer->name,
                'phone' => $customer->phone,
            ]);

            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'egp',
                        'product_data' => ['name' => 'Rental - ' . $item->name],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'customer' => $stripeCustomer->id,
                'success_url' => route('api.rent.callback', ['id' => $rent->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('api.rent.error'),
                'metadata' => [
                    'item_id' => $item->id,
                    'rental_id' => $rent->id,
                ],
            ]);

            $rent->payment_token = $checkout_session->id;
            $rent->save();

            return response()->json(['session_id' => $checkout_session->id]);

        } catch (\Exception $e) {
            Log::error('Stripe error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // الدفع تم بنجاح
    public function callback($id, Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::retrieve($request->get('session_id'));

        $rental = Rent::find($id);
        if (!$rental) {
            return response()->json(['error' => 'Rental not found'], 404);
        }

        $rental->payment_token = $session->id;
        $rental->save();

       // زيادة score للعميل بمقدار 10
       $customer = $rental->customer;
       if ($customer) {
           $customer->incrementScore(); // أو $customer->increment('score', 10);
       }

       return response()->json([
           'message' => 'Payment successful, score updated',
           'rental' => $rental,
           'score' => $customer->score ?? 0,
       ]);
    }

    // فشل الدفع
    public function error()
    {
        return response()->json(['error' => 'Payment canceled or failed'], 400);
    }
}
