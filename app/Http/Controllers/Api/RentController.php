<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class RentController extends Controller
{     //customer

    public function rentHistory(Request $request)
    {
        $customer = Auth::guard('customer')->user();
    
        if (!$customer) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $rentals = $customer->rentals()
            ->with('item')
            ->latest('rented_time')
            ->get();
    
        return response()->json([
            'status' => 'success',
            'rentals' => $rentals
        ]);
    }
    

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


    public function pendingRequests()
    {
        $customerId = auth('customer')->id();

        $pendingRents = Rent::with('item')
            ->where('customer_id', $customerId)
            ->where('rental_status', 'pending')
            ->get();

        return response()->json([
            'pending_requests' => $pendingRents
        ]);
    }

    public function cancelRequest($id)
    {
        $customerId = auth('customer')->id();

        $rent = Rent::where('id', $id)
            ->where('customer_id', $customerId)
            ->where('rental_status', 'pending')
            ->firstOrFail();

        $rent->delete();

        return response()->json([
            'message' => 'Rental request removed successfully.'
        ]);
    }



    //lender
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
    ////////////////

    public function store(Request $request)
    {
        try {
            $request->validate([
                'item_id' => 'required|exists:items,id',
                'start_date' => 'required|date|after_or_equal:' . now()->toDateString(),
                'end_date' => 'required|date|after_or_equal:start_date',
                'delivery_address' => 'required|string|max:100',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    
        $customer = auth('customer')->user();
        if (!$customer) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $rent = Rent::where('customer_id', $customer->id)
            ->where('item_id', $request->item_id)
            ->where('rental_status', 'approved')
            ->first();
    
        $overlap = Rent::where('item_id', $request->item_id)
            ->when($rent, fn($query) => $query->where('id', '!=', $rent->id))
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
            $rent->update([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'delivery_address' => $request->delivery_address,
                'rental_status' => 'in_progress',
            ]);
    
            Item::findOrFail($request->item_id)->update(['item_status' => 'unavailable']);
    
            return response()->json([
                'message' => 'Rent updated successfully',
                'rent' => $rent,
            ], 200);
        }
    
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
        $customer = $rental->customer;
        if ($customer) {
            $customer->incrementScore();
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
