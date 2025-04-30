<!-- resources/views/customer/login.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .error-message {
            color: red;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        .error-message ul {
            margin: 0;
            padding: 0;
        }
        .error-message li {
            list-style: none;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        input:focus, button:focus {
            border-color: #4CAF50;
            outline: none;
        }
        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            border: none;
            font-size: 1.1rem;
        }
        button:hover {
            background-color: #45a049;
        }
        p {
            text-align: center;
            font-size: 0.9rem;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Customer Login</h2>

        @if ($errors->any())
            <div class="error-message">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('customer.doLogin') }}">
            @csrf
            <input name="email" type="email" placeholder="Email" value="{{ old('email') }}"><br>
            <input name="password" type="password" placeholder="Password"><br>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="{{ route('customer.register.form') }}">Register here</a></p>
    </div>

</body>
</html>
