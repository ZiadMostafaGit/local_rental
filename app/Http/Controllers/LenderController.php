<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Lender;
use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LenderController extends Controller
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
     // عرض صفحة التسجيل
     public function showRegisterForm()
     {
         return view('lender.register');
     }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $lender = Lender::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'state' => $request->state,
            'city' => $request->city,
            'street' => $request->street,
        ]);


        return redirect()->route('lender.dashboard');
    }



    /**
     * Display the specified resource.
     */



 public function dashboard(Request $request)
 {
    return $this->showRequests();
 }
 public function showRequests()
{
    $lenderId = 8;

$requests = Rent::where('rental_status', 'pending')
                ->whereHas('item', function ($query) use ($lenderId) {
                    $query->where('lender_id', $lenderId);
                })
                ->get();


    return view('lender.dashboard', compact('requests'));
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
     * Show the form for editing the specified resource.
     */
    public function edit(Lender $lender)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lender $lender)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lender $lender)
    {
        //
    }
}
