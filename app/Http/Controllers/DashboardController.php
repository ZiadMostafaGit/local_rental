<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Lender;
use App\Models\Review;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {
        return view('admin_dashboard.pages.dashboard');
    }
    public function gettotalitem()
    {
        $totalitem = Item::count();
        return response()->json(['total' => $totalitem]);
    }
    public function gettotalcategory()
    {
        $totalcategory = Category::count();
        return response()->json(['total' => $totalcategory]);
    }
    public function gettotallender()
    {
        $totallender = Lender::count();
        return response()->json(['total' => $totallender]);
    }
    public function gettotalcustomer()
    {
        $totalcustomer = Customer::count();
        return response()->json(['total' => $totalcustomer]);
    }


   public function getAllRentsChart(Request $request)
{
    $range = $request->get('range');
    $fromDate = $request->get('from');
    $toDate = $request->get('to');

    if ($fromDate && $toDate) {
        $from = Carbon::parse($fromDate)->startOfDay();
        $to = Carbon::parse($toDate)->endOfDay();
    } else {
        switch ($range) {
            case 'last_month':
                $from = now()->subMonth();
                break;
            case 'last_6_month':
                $from = now()->subMonths(6);
                break;
            case 'last_year':
                $from = now()->subYear();
                break;
            default:
                $from = now()->subWeek();
        }
        $to = now();
    }

    $rents = DB::table('rents')
        ->select(DB::raw('DATE(start_date) as date'), DB::raw('count(*) as total'))
        ->whereBetween('start_date', [$from, $to])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    return response()->json($rents);
}

    public function showReport()
    {
        $customers = Customer::select('first_name', 'score')->get();

        $labels = $customers->pluck('first_name');
        $scores = $customers->pluck('score');

        return response()->json([
            'labels' => $labels,
            'scores' => $scores,
        ]);
    }
    public function showLenderReport()
{
    $lenders = Lender::select('first_name', 'score')->get();

    $labels = $lenders->pluck('first_name');
    $scores = $lenders->pluck('score');

    return response()->json([
        'labels' => $labels,
        'scores' => $scores,
    ]);
}

    public function getTopRentedItems()
    {
        $items = Item::withCount('rents')
            ->having('rents_count', '>', 1) // ⬅️ الشرط المطلوب
            ->orderByDesc('rents_count')
            ->take(5)
            ->get();

        return response()->json($items);
    }

    public function getAllLenders()
    {
        $lenders = Lender::with('phoneNumbers')->get();

        $data = $lenders->map(function ($lender) {
            return [
                'full_name'   => $lender->first_name . ' ' . $lender->last_name,
                'email'       => $lender->email,
                'gender'      => $lender->gender,
                'state'       => $lender->state,
                'city'        => $lender->city,
                'street'      => $lender->street,
                'score'       => $lender->score,
                'phoneNumbers' => $lender->phoneNumbers->pluck('phone_number'), // assumes column is phone_number
            ];
        });
    }


    public function getAllCustomers()
    {
        $customers = Customer::with('phoneNumbers')->get();

        $data = $customers->map(function ($customer) {
            return [
                'id'          => $customer->id,
                'full_name'   => $customer->first_name . ' ' . $customer->last_name,
                'email'       => $customer->email,
                'gender'      => $customer->gender,
                'state'       => $customer->state,
                'city'        => $customer->city,
                'street'      => $customer->street,
                'score'       => $customer->score,
                'phoneNumbers' => $customer->phoneNumbers->pluck('phone_number'),
            ];
        });

        return response()->json($data);
    }



    public function getAllCategories()
    {
        $categories = Category::all();

        return response()->json($categories);
    }


    public function getAllRents()
    {
        $rents = DB::table('rents')
            ->join('items', 'rents.item_id', '=', 'items.id')
            ->join('customers', 'rents.customer_id', '=', 'customers.id')
            ->select(
                'rents.id',
                'items.name as item_name',
                'customers.first_name',
                'customers.last_name',
                'rents.start_date',
                'rents.end_date',
                'rents.total_price'
            )
            ->get();

        return response()->json($rents);
    }





    public function getAllReviews()
    {
        $reviews = DB::table('reviews')
            ->join('items', 'reviews.item_id', '=', 'items.id')
            ->join('customers', 'reviews.customer_id', '=', 'customers.id')
            ->select(
                'reviews.id',
                'items.name as item_name',
                'customers.first_name',
                'customers.last_name',
                'reviews.rating',
                'reviews.comment'
            )
            ->get();

        return response()->json($reviews);
    }




}
