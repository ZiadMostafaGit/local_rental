<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Notifications\CustomerNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::with('phoneNumbers')->get();

        $data = $customers->map(function ($customer) {
            return [
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
        return view('admin_dashboard.pages.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin_dashboard.pages.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'      => 'required|email|unique:customers,email',
            'gender'     => 'required|in:male,female,other',
            'state'      => 'nullable|string',
            'city'       => 'nullable|string',
            'street'     => 'nullable|string',
            'score'      => 'nullable|numeric',
            'phoneNumbers' => 'required|array',
            'phoneNumbers.*' => 'required|string',
        ]);

        $customer = Customer::create($validated);

        foreach ($validated['phoneNumbers'] as $number) {
            $customer->phoneNumbers()->create([
                'phone_number' => $number,
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'تمت إضافة العميل بنجاح');
    }


    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id);

        return view('admin_dashboard.pages.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $customer = Customer::with('phoneNumbers')->findOrFail($id);

    // التحقق من صحة البيانات
    $validated = $request->validate([
        'first_name' => 'required|string',
        'last_name'  => 'required|string',
        'email'      => 'required|email|unique:customers,email,' . $customer->id,
        'gender'     => 'required|in:M,F',
        'state'      => 'nullable|string',
        'city'       => 'nullable|string',
        'street'     => 'nullable|string',
        'score'      => 'nullable|numeric',
        'phoneNumbers' => 'nullable|array',  // يمكن أن تكون فارغة
        'phoneNumbers.*' => 'nullable|string', // يمكن أن تكون فارغة أو تحتوي على نص
    ]);

    // تحديث بيانات العميل
    $customer->update($validated);

    // إضافة أو تحديث الأرقام فقط إذا كانت موجودة (اختياري)
    if (isset($validated['phoneNumbers']) && is_array($validated['phoneNumbers'])) {
        // التأكد من أن الأرقام غير فارغة ثم إضافة الأرقام الجديدة فقط
        foreach ($validated['phoneNumbers'] as $number) {
            if (!empty($number)) {
                // إذا كانت الأرقام جديدة وليست فارغة، نضيفها
                $customer->phoneNumbers()->create([
                    'phone_num' => $number,
                ]);
            }
        }
    }

    // إعادة التوجيه مع رسالة نجاح
    return redirect()->route('customers.index')->with('success', 'تم تحديث بيانات العميل بنجاح');
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        $customer->phoneNumbers()->delete();
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'تم حذف العميل بنجاح');
    }





      public function send_mail( $id) {
        $customer = Customer::find($id);
        return view('admin_dashboard.pages.customers.mail',compact('customer'));
    }

    public function mail(Request $request, $id) {
        $customer = Customer::find($request->id);
         $details =[
            'greeting' => $request->greeting,
            'body' => $request->body,
            'action_text' => $request->action_text,
            'action_url' => $request->action_url,
            'endline' => $request->endline
         ];

         Notification::send($customer, new CustomerNotification($details));

        return redirect()->back()->with('success', 'تم إرسال البريد الإلكتروني بنجاح');
    }
}
