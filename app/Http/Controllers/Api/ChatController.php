<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Customer;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function show($conversationId)
    {
        $conversation = Conversation::with(['messages', 'lender', 'customer', 'item'])
            ->where('id', $conversationId)
            ->first();
    
        if (!$conversation) {
            return response()->json(['error' => 'المحادثة غير موجودة'], 404);
        }
    
        $authData = $this->getAuthenticatedUserAndType();
        $user = $authData['user'];
        $type = $authData['sender'];
    
        if (
            !$user ||
            ($type === 'customer' && $conversation->customer_id !== $user->id) ||
            ($type === 'lender' && $conversation->lender_id !== $user->id)
        ) {
            return response()->json(['error' => 'غير مصرح بالدخول إلى هذه المحادثة'], 403);
        }
    
        return response()->json($conversation);
    }
    


    public function createConversation(Request $request)
    {
        $validated = $request->validate([
            'lender_id' => 'required|exists:lenders,id',
            'customer_id' => 'required|exists:customers,id',
            'item_id' => 'required|exists:items,id',
        ]);
    
        // تحقق من الصلاحيات لو حبيت (مثلاً لازم المستخدم الحالي يكون هو الـ customer)
    
        $existing = Conversation::where('lender_id', $validated['lender_id'])
            ->where('customer_id', $validated['customer_id'])
            ->where('item_id', $validated['item_id'])
            ->first();
    
        if ($existing) {
            return response()->json([
                'message' => 'المحادثة موجودة بالفعل.',
                'conversation' => $existing
            ], 200);
        }
    
        $conversation = Conversation::create($validated);
    
        return response()->json([
            'message' => 'تم إنشاء المحادثة بنجاح.',
            'conversation' => $conversation
        ], 201);
    }
    
    // إرسال رسالة جديدة
    public function sendMessage(Request $request, $conversationId)
    {
        $authData = $this->getAuthenticatedUserAndType();
        $user = $authData['user'];
        $type = $authData['sender'];
        
    
        if (!$user) {
            return response()->json(['error' => 'غير مصرح'], 401);
        }
    
        $message = Message::create([
            'conversation_id' => $conversationId,
            'message_content' => $request->message_content,
            'sender' => $type
        ]);
    
        broadcast(new MessageSent($message))->toOthers();
    
        return response()->json($message);
    }
    
    // جلب الرسائل للمحادثة
    public function getMessages($conversationId)
    {
        $messages = Message::where('conversation_id', $conversationId)->get();

        return response()->json($messages);
    }

    protected function getAuthenticatedUserAndType()
    {
        if (auth('customer')->check()) {
            return ['user' => auth('customer')->user(), 'sender' => 'customer'];
        } elseif (auth('lender')->check()) {
            return ['user' => auth('lender')->user(), 'sender' => 'lender'];
        }

        return [null, null];
    }
}
