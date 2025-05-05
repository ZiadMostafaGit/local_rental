<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Register</title>
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
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        input:focus, select:focus, button:focus {
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
    <h2>Customer Registration</h2>

    @if ($errors->any())
        <div class="error-message">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('customer.register') }}">
        @csrf

        <input name="first_name" placeholder="First Name" value="{{ old('first_name') }}">

        <input name="last_name" placeholder="Last Name" value="{{ old('last_name') }}">

        <input name="email" type="email" placeholder="Email" value="{{ old('email') }}">

        <input name="password" type="password" placeholder="Password">
        <input name="password_confirmation" type="password" placeholder="Confirm Password">

        <select name="gender">
            <option value="">Select Gender</option>
            <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Male</option>
            <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Female</option>
        </select>

        <input name="score" type="number" placeholder="Score" value="{{ old('score') }}">

        <input name="state" placeholder="State" value="{{ old('state') }}">
        <input name="city" placeholder="City" value="{{ old('city') }}">
        <input name="street" placeholder="Street" value="{{ old('street') }}">

        <label for="phone_numbers[]">Phone Numbers:</label>
        <input type="text" name="phone_numbers[]" placeholder="Phone Number 1" value="{{ old('phone_numbers.0') }}">
        <input type="text" name="phone_numbers[]" placeholder="Phone Number 2 (optional)" value="{{ old('phone_numbers.1') }}">

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="{{ route('customer.login') }}">Login here</a></p>
</div>

</body>
</html>
