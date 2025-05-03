<style>
    /* تحسين تنسيق الحاوية */
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* تنسيق العنوان */
h1 {
    font-size: 2rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 15px;
}

/* تحسين الفقرة مع الوصف */
.lead {
    font-size: 1.2rem;
    color: #555;
}

/* تنسيق الرسائل التنبيه */
.alert {
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 15px;
    font-size: 1rem;
}

/* تنسيق رسالة النجاح */
.alert-success {
    background-color: #d4edda;
    color: #155724;
}

/* تنسيق رسالة المعلومات */
.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
}

/* تنسيق رسالة الخطأ */
.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
}

/* تحسين تنسيق الزر */
.btn {
    padding: 10px 20px;
    font-size: 1rem;
    font-weight: bold;
    border-radius: 5px;
    text-align: center;
    text-decoration: none;
}

/* زر الإرسال */
.btn-primary {
    background-color: #007bff;
    color: white;
    border: none;
}

.btn-primary:hover {
    background-color: #0056b3;
}

/* تحسين الزر الأكمل الآن */
.btn-sm {
    padding: 8px 15px;
}

/* زر إرسال طلب الإيجار بحجم كامل */
.btn-lg {
    padding: 15px 20px;
    font-size: 1.2rem;
}

/* زيادة المسافة حول الأزرار */
.mt-2 {
    margin-top: 10px;
}

.mt-4 {
    margin-top: 30px;
}
</style>
<div class="container">
    <h1 class="mb-4">{{ $item->title }}</h1>
    <p class="lead"><strong>Description:</strong> {{ $item->description }}</p>
    <p><strong>Price per day:</strong> ${{ $item->price }}</p>

    {{-- رسائل التنبيه --}}
    <div class="mt-3">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('info') && session('approved_rent_id'))
            <div class="alert alert-info">
                {{ session('info') }}
                <a href="{{ route('rent.form', ['rent_id' => session('approved_rent_id')]) }}" class="btn btn-sm btn-primary mt-2">أكمل الآن</a>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- زر الإرسال يظهر فقط إذا لا يوجد موافقة أو رفض --}}
    @if (!session('approved_rent_id') && !session('error'))
        <div class="mt-4">
            <form method="POST" action="{{ route('rent.request') }}">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <button type="submit" class="btn btn-primary btn-lg w-100">Send Rent Request</button>
            </form>
        </div>
    @endif
</div>
