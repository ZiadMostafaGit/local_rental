<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Lender;
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
        $range = $request->get('range', 'last_week');

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

        $rents = DB::table('rents')
            ->select(DB::raw('DATE(start_date) as date'), DB::raw('count(*) as total'))
            ->where('start_date', '>=', $from)
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
public function getTopRentedItems()
{
    $items = Item::withCount('rents')
                ->having('rents_count', '>', 1) // ⬅️ الشرط المطلوب
                ->orderByDesc('rents_count')
                ->take(5)
                ->get();

    return response()->json($items);
}

}
