<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function showRegistrationForm()
    {
        return view('customer.register'); // اسم ملف الـ Blade
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'            => 'required|string|max:255',
            'last_name'             => 'required|string|max:255',
            'email'                 => 'required|string|email|max:255|unique:customers,email',
            'password'              => 'required|string|confirmed|min:6',
            'gender'                => 'required|in:M,F',
            'score'                 => 'nullable|numeric|min:0',
            'state'                 => 'nullable|string|max:255',
            'city'                  => 'nullable|string|max:255',
            'street'                => 'nullable|string|max:255',
            'phone_numbers'         => 'required|array|min:1',
            'phone_numbers.*'       => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $customer = Customer::create([
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'gender'        => $request->gender,
            'score'         => $request->score,
            'state'         => $request->state,
            'city'          => $request->city,
            'street'        => $request->street,
            'phone_numbers' => json_encode($request->phone_numbers),
        ]);

        auth('customer')->login($customer); // لو عندك customer guard

        return redirect()->route('customer.dashboard'); // عدل هذا المسار حسب ما عندك
    }

    public function showLoginForm()
    {
        return view('customer.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

          // استخدام الـ guard المخصص للعملاء
          if (Auth::guard('customer')->attempt($credentials, $request->filled('remember'))) {
            // إعادة إنشاء الجلسة
            $request->session()->regenerate();

            // إعادة التوجيه إلى صفحة الـ dashboard
            return redirect()->intended(route('customer.dashboard'));
        }

        // في حال فشل التحقق من البيانات
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }
}
