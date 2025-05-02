<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تمت العملية بنجاح</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
            direction: rtl;
        }

        .container {
            max-width: 600px;
            margin: 80px auto;
            background-color: #e6ffed;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            color: #155724;
        }

        h2 {
            text-align: center;
            color: #155724;
            margin-bottom: 20px;
        }

        p {
            font-size: 17px;
            margin: 12px 0;
        }

        code {
            background-color: #d4edda;
            padding: 3px 6px;
            border-radius: 5px;
            font-size: 15px;
        }

        .btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container text-center">
    <h2>تمت العملية بنجاح ✅</h2>
    <p>اسم العميل: <strong>{{ $rental->customer->first_name }}</strong></p>
    <p>بداية الإيجار: <code>{{ $rental->start_date }}</code></p>
    <p>نهاية الإيجار: <code>{{ $rental->end_date }}</code></p>
    <p>عنوان الإيصال: <code>{{ $rental->delivery_address }}</code></p>
    <p>شكراً لاستئجارك المنتج: <strong>{{ $rental->item->title }}</strong></p>
    <p>:التارجت <strong>{{ $rental->customer->score }}</strong></p>
    <p>رقم العملية: <code>{{ $rental->payment_token }}</code></p>
    <a href="{{ route('home') }}" class="btn">العودة للصفحة الرئيسية</a>
</div>

</body>
</html>
