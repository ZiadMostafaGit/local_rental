<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'comment' => 'nullable|string',
            'rating' => 'required|numeric|min:0|max:5',
        ]);

        $customerId = auth('customer')->id();

        $exists = Review::where('customer_id', $customerId)
            ->where('item_id', $request->item_id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'You already reviewed this item.'], 409);
        }

        $review =  Review::create([
            'customer_id' => $customerId,
            'item_id' => $request->item_id,
            'comment' => $request->comment,
            'rating' => $request->rating,
        ]);

        return response()->json(['message' => 'Review submitted successfully.', $review], 201);
    }


    // عرض العملاء الذين كتبوا تقييم لمنتج معين
    public function customersWhoReviewedItem($itemId)
    {
        $reviews = Review::with('customer')
            ->where('item_id', $itemId)
            ->get();

        // ترتيب المراجعات حسب أعلى تقييم
        $sortedReviews = $reviews->sortByDesc('rating');

        $result = $sortedReviews->map(function ($review) {
            return [
                'customer_id' => $review->customer->id,
                'first_name' => $review->customer->first_name,
                'last_name' => $review->customer->last_name,
                'email' => $review->customer->email,
                'comment' => $review->comment,
                'rating' => $review->rating,
            ];
        });

        return response()->json($result);
    }
}
