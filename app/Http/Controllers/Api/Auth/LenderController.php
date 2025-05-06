<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Lender;
use App\Models\Rent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LenderController extends Controller
{

    public function profile(Request $request)
    {
        $lender = Auth::guard('lender')->user();

        if (!$lender) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $lender = Lender::with('phoneNumbers')->find($lender->id);
        return response()->json($lender);
    }

    // تسجيل مؤجِّر جديد
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'email'      => 'required|email|unique:lenders,email',
            'password'   => 'required|string|min:6',
            'gender'     => 'required|in:M,F',
            'state'      => 'required|string',
            'city'       => 'required|string',
            'street'     => 'required|string',
            'score'      => 'required|numeric',
            'phone_nums' => 'required|array', 
            'phone_nums.*' => 'string|unique:lender_phone_num,phone_num', 
        ]);

        $lender = Lender::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'gender'     => $request->gender,
            'state'      => $request->state,
            'city'       => $request->city,
            'street'     => $request->street,
            'score'      => $request->score,
        ]);

        // إضافة الأرقام إلى قاعدة البيانات
        foreach ($request->phone_nums as $phone_num) {
            $lender->phoneNumbers()->create([
                'phone_num' => $phone_num,
            ]);
        }

        $token = $lender->createToken('lender_token')->plainTextToken;

        return response()->json([
            'message' => 'Lender registered successfully.',
            'lender'  => $lender,
            'token'   => $token,
        ], 201);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $lender = Lender::where('email', $request->email)->first();

            if ($lender && Hash::check($request->password, $lender->password)) {
                $token = $lender->createToken('lender_token')->plainTextToken;
                return response()->json([
                    'status' => 'Login successfully',
                    'token' => $token
                ]);
            } else {
                return response()->json([
                    'status' => 'Login failed',
                    'msg' => 'Invalid email or password'
                ], 401);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'msg' => $e->getMessage()
            ], 500);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    // عرض الطلبات المعلّقة
    public function showRequests()
    {
        $lender = auth('lender')->user();

        if (!$lender) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // الحصول على الطلبات المعلقة التي تخص العناصر التي يمتلكها الـ lender
        $requests = Rent::where('rental_status', 'pending')
            ->whereHas('item', function ($query) use ($lender) {
                $query->where('lender_id', $lender->id);
            })
            ->get();

        return response()->json([
            'pending_requests' => $requests
        ]);
    }

    // الموافقة على طلب
    public function approveRequest($id)
    {
        $lender = auth('lender')->user();

        if (!$lender) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $rent = Rent::find($id);
        if (!$rent) {
            return response()->json(['error' => 'Request not found'], 404);
        }

        $rent->rental_status = 'approved';
        $rent->save();

        return response()->json(['message' => 'Request approved']);
    }

    // رفض طلب
    public function rejectRequest($id)
    {
        $lender = auth('lender')->user();

        if (!$lender) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $rent = Rent::find($id);
        if (!$rent) {
            return response()->json(['error' => 'Request not found'], 404);
        }

        $rent->rental_status = 'rejected';
        $rent->save();

        return response()->json(['message' => 'Request rejected']);
    }
}
