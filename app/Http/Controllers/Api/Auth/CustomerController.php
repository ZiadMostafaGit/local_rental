<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;


use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
            $customer = Customer::where('email', $request->email)->first();

            if ($customer && Hash::check($request->password, $customer->password)) {
                $token = $customer->createToken('customer_token')->plainTextToken;
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

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'gender'     => 'required|in:M,F', 
            'score'      => 'required|numeric',
            'state'      => 'required|string|max:100',
            'city'       => 'required|string|max:100',
            'street'     => 'required|string|max:100',
            'email'      => 'required|email|unique:customers,email',
            'password'   => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $customer = Customer::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'score' => $request->score,
                'state' => $request->state,
                'city' => $request->city,
                'street' => $request->street,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $customer->createToken('customer_token')->plainTextToken;

            return response()->json([
                'status' => 'Register successfully',
                'token' => $token
            ], 201);
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
}
