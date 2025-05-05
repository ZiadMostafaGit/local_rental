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
    public function rentRequest(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
        ]);

        $customerId = auth('customer')->id();

        $rent = Rent::create([
            'customer_id' => $customerId,
            'item_id' => $request->item_id,
            'rental_status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Rental request sent successfully.',
            'rent' => $rent->fresh()
        ], 201);

    }
    public function approveRequest($id)
    {
        $rent = Rent::findOrFail($id);
        $rent->rental_status = 'approved';
        $rent->save();

        return response()->json(['message' => 'Request approved.', 'rent' => $rent]);
    }

    public function rejectRequest($id)
    {
        $rent = Rent::findOrFail($id);
        $rent->rental_status = 'rejected';
        $rent->save();

        return response()->json(['message' => 'Request rejected.', 'rent' => $rent]);
    }


    // إنشاء إيجار جديد
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'start_date' => 'required|date|after_or_equal:' . today()->toDateString(),
            'end_date' => 'required|date|after_or_equal:start_date',
            'delivery_address' => 'required|string|max:100',
        ]);

        $customer = auth('customer')->user();
        if (!$customer) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // البحث عن تأجير سابق تمّت الموافقة عليه
        $rent = Rent::where('customer_id', $customer->id)
                    ->where('item_id', $request->item_id)
                    ->where('rental_status', 'approved')
                    ->first();

        // تحقق من التداخل مع حجوزات أخرى لنفس العنصر
        $overlap = Rent::where('item_id', $request->item_id)
            ->when($rent, function ($query) use ($rent) {
                return $query->where('id', '!=', $rent->id); // استثناء الحجز الحالي إن وجد
            })
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                      });
            })
            ->exists();

        if ($overlap) {
            return response()->json(['error' => 'The item is already rented during this period.'], 422);
        }

        if ($rent) {
            // تحديث بيانات الإيجار
            $rent->update([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'delivery_address' => $request->delivery_address,
                'rental_status' => 'in_progress',
            ]);

            // تحديث حالة العنصر
            $item = Item::findOrFail($request->item_id);
            $item->update(['item_status' => 'unavailable']);

            return response()->json([
                'message' => 'Rent updated successfully',
                'rent' => $rent,
            ], 200);
        } else {
            // إنشاء سجل جديد
            $rent = Rent::create([
                'customer_id' => $customer->id,
                'item_id' => $request->item_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'delivery_address' => $request->delivery_address,
                'rental_status' => 'pending',
            ]);

            return response()->json([
                'message' => 'Rent created successfully',
                'rent' => $rent,
            ], 201);
        }
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

        $item = $rental->item;
        $lender = $item->lender; //

        if ($lender) {
            $lender->increment('score', 10);
        }

        $customer = $rental->customer;
        if ($customer) {
            $customer->incrementScore();
        }

        return response()->json([
            'message' => 'Payment successful, score updated',
            'rental' => $rental,
            'score' => $customer->score ?? 0,
            'score' => $lender->score ?? 0,
        ]);
    }

    // فشل الدفع
    public function error()
    {
        return response()->json(['error' => 'Payment canceled or failed'], 400);
    }
}
