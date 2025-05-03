<style>
    /* تحسين تنسيق العنوان */
.page-title {
    font-size: 2rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 20px;
}

/* تنسيق الرسائل */
.alert {
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 20px;
    font-size: 1rem;
}

/* تنسيق رسالة النجاح */
.alert-success {
    background-color: #d4edda;
    color: #155724;
}

/* تنسيق رسالة الخطأ */
.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
}

/* تحسين تنسيق البطاقات */
.request-card {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
}

/* تحسين تنسيق الأزرار */
.btn {
    padding: 8px 20px;
    font-size: 1rem;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
}

/* زر الموافقة */
.btn-success {
    background-color: #28a745;
    color: white;
    border: none;
}

.btn-success:hover {
    background-color: #218838;
}

/* زر الرفض */
.btn-danger {
    background-color: #dc3545;
    color: white;
    border: none;
}

.btn-danger:hover {
    background-color: #c82333;
}

/* ترتيب الأزرار */
.request-actions {
    margin-top: 15px;
}

</style>
<h2 class="page-title">Rental Requests</h2>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@elseif(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if(isset($requests) && count($requests) > 0)
    @foreach ($requests as $request)
        <div class="request-card mt-4 p-4 bg-white border rounded shadow-sm">
            <p><strong>Item:</strong> {{ $request->item->title }}</p>
            <p><strong>Price:</strong> ${{ $request->item->price }}</p>
            <p><strong>Customer:</strong> {{ $request->customer->first_name }} {{ $request->customer->last_name }}</p>
            <p><strong>Email:</strong> {{ $request->customer->email }}</p>
            <p><strong>City:</strong> {{ $request->customer->city }}</p>
            <p><strong>State:</strong> {{ $request->customer->state }}</p>
            <p><strong>Street:</strong> {{ $request->customer->street }}</p>

            <div class="request-actions">
                <form action="{{ route('lender.approve', $request->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>

                <form action="{{ route('lender.reject', $request->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Reject</button>
                </form>
            </div>
        </div>
    @endforeach
@else
    <p>No rental requests at the moment.</p>
@endif
