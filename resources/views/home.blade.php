<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مرحباً بك</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            background: linear-gradient(to bottom, #f8f9fa, #e9ecef);
            margin: 0;
            padding: 0;
            direction: rtl;
        }

        .container {
            max-width: 700px;
            margin: 100px auto;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #343a40;
        }

        p.lead {
            font-size: 18px;
            margin-bottom: 30px;
            color: #6c757d;
        }

        hr {
            border: 0;
            height: 1px;
            background-color: #dee2e6;
            margin: 30px 0;
        }

        .btn {
            display: inline-block;
            background-color: #28a745;
            color: #fff;
            padding: 14px 30px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #218838;
        }

        @media (max-width: 600px) {
            .container {
                margin: 50px 20px;
                padding: 30px 20px;
            }

            h1 {
                font-size: 24px;
            }

            .btn {
                padding: 12px 24px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>مرحباً بك في نظام تأجير المنتجات! 🛒</h1>
    <p class="lead">يمكنك استعراض المنتجات واستئجار ما تحتاجه بسهولة وأمان.</p>
    <hr>
    <a class="btn" href="{{ route('rent.form') }}">ابدأ الاستئجار</a>
</div>

</body>
</html>
