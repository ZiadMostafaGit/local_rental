<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Lender;
use App\Notifications\LenderNotifiaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class LenderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lenders = Lender::with('phoneNumbers')->get();
        return view('admin_dashboard.pages.lenders.index', compact('lenders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin_dashboard.pages.lenders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'      => 'required|email|unique:lenders,email',
            'gender'     => 'required|in:male,female,other',
            'state'      => 'nullable|string',
            'city'       => 'nullable|string',
            'street'     => 'nullable|string',
            'score'      => 'nullable|numeric',
            'phoneNumbers' => 'required|array',
            'phoneNumbers.*' => 'required|string',
        ]);

        // إنشاء المقرض
        $lender = Lender::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'gender'     => $validated['gender'],
            'state'      => $validated['state'],
            'city'       => $validated['city'],
            'street'     => $validated['street'],
            'score'      => $validated['score'],
        ]);

        // إضافة أرقام الهاتف
        foreach ($validated['phoneNumbers'] as $number) {
            $lender->phoneNumbers()->create([
                'phone_number' => $number,
            ]);
        }

        return redirect()->route('lenders.index')->with('success', 'تمت إضافة المؤجر بنجاح');
    }



    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $lender = Lender::with('phoneNumbers')->findOrFail($id);


        return view('admin_dashboard.pages.lenders.edit', compact('lender'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $lender = Lender::with('phoneNumbers')->findOrFail($id);

        // التحقق من صحة البيانات
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'      => 'required|email|unique:lenders,email,' . $lender->id,
            'gender'     => 'required|in:M,F',
            'state'      => 'nullable|string',
            'city'       => 'nullable|string',
            'street'     => 'nullable|string',
            'score'      => 'nullable|numeric',
            'phoneNumbers' => 'nullable|array',
            'phoneNumbers.*' => 'nullable|string',
        ]);

        // تحديث بيانات الـ lender
        $lender->update($validated);

        // إضافة الأرقام الجديدة فقط إذا كانت موجودة وغير فارغة
        if (isset($validated['phoneNumbers']) && is_array($validated['phoneNumbers'])) {
            foreach ($validated['phoneNumbers'] as $number) {
                if (!empty($number)) {
                    // إضافة الرقم فقط إذا لم يكن فارغًا
                    $lender->phoneNumbers()->create([
                        'phone_num' => $number,
                    ]);
                }
            }
        }

        return redirect()->route('lenders.index')->with('success', 'تم تحديث المؤجر بنجاح');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $lender = Lender::findOrFail($id);

        // حذف أرقام الهاتف المرتبطة أولاً (إن لم يكن محدد cascade في قاعدة البيانات)
        $lender->phoneNumbers()->delete();

        // حذف المقرض
        $lender->delete();

        return redirect()->route('lenders.index')->with('success', 'تم حذف المؤجر بنجاح');
    }



    // عرض كل العناصر المعلقة للمراجعة
    public function pendingItems()
    {
        $items = Item::where('item_status', 'pending')->with('lender')->get();

        return  view('admin_dashboard.pages.lenders.pending', compact('items'));
    }

    // الموافقة على عنصر
    public function approve($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return redirect()->back()->with('error', 'العنصر غير موجود.');
        }

        if ($item->item_status !== 'pending') {
            return redirect()->back()->with('error', 'العنصر ليس قيد المراجعة.');
        }

        $item->item_status = 'available';
        $item->save();

        return redirect()->back()->with('success', 'تمت الموافقة على العنصر بنجاح.');
    }
    public function reject($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return redirect()->back()->with('error', 'العنصر غير موجود.');
        }

        if ($item->item_status !== 'pending') {
            return redirect()->back()->with('error', 'العنصر ليس قيد المراجعة.');
        }

        $item->item_status = 'unavailable';
        $item->save();

        return redirect()->back()->with('success', 'تم رفض العنصر.');
    }


    public function send_mail( $id) {
        $lender = Lender::find($id);
        return view('admin_dashboard.pages.lenders.send_mail',compact('lender'));
    }

    public function mail(Request $request, $id) {
        $lender = Lender::find($request->id);
         $details =[
            'greeting' => $request->greeting,
            'body' => $request->body,
            'action_text' => $request->action_text,
            'action_url' => $request->action_url,
            'endline' => $request->endline
         ];

         Notification::send($lender, new LenderNotifiaction($details));

        return redirect()->back()->with('success', 'تم إرسال البريد الإلكتروني بنجاح');
    }
}
