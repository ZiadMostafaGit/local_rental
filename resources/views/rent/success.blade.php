
<div class="container mt-5">
    <div class="alert alert-success text-center">
        <h2>تمت العملية بنجاح ✅</h2>
        <p> اسم العميل: <strong>{{ $rental->customer->first_name}}</strong></p>
        <p> بدايه الايجار: <code>{{ $rental->start_date }}</code></p>
        <p> نهايه الايجار: <code>{{ $rental->end_date }}</code></p>
        <p> عتوان الايصال: <code>{{ $rental->delivery_address }}</code></p>
        <p>شكراً لاستئجارك المنتج: <strong>{{ $rental->item->name }}</strong></p>
        <p>رقم العملية: <code>{{ $rental->payment_token }}</code></p>
        <a href="{{ route('home') }}" class="btn btn-primary mt-3">العودة للصفحة الرئيسية</a>
    </div>
</div>

