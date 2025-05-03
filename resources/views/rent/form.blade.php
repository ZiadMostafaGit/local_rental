<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rent an Item</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* نفس التنسيقات كما هي بدون تعديل */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 60px auto;
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #555555;
        }

        input[type="date"],
        input[type="text"] {
            width: 100%;
            padding: 10px 12px;
            font-size: 15px;
            border: 1px solid #cccccc;
            border-radius: 8px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input:focus {
            border-color: #007bff;
            outline: none;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            background-color: #007bff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .alert {
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Rent an Item</h2>

    <!-- عرض رسائل النجاح أو الإعلام -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <!-- عرض رسائل الخطأ -->
    @if ($errors->any())
        <div class="error-message">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="rent-form" method="POST" action="{{ route('rent.store') }}">
        @csrf

        {{-- تمرير رقم الطلب المعتمد --}}
        @if(isset($rent))
            <input type="hidden" name="rent_id" value="{{ $rent->id }}">
        @endif

        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" id="start_date" name="start_date" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" id="end_date" name="end_date" required>
        </div>
        <div class="form-group">
            <label for="delivery_address">Delivery Address</label>
            <input type="text" id="delivery_address" name="delivery_address" required>
        </div>
        <button type="submit" class="btn">Proceed to Payment</button>
    </form>
</div>

</body>
</html>
