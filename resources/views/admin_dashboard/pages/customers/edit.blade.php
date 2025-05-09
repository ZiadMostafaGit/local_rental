@extends('admin_dashboard.layout.pages-layout')

@section('pagetitle', 'Dashboard')
@section('content')
<title>Edit Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

    <div class="container">
        <h1 class="mb-4">Edit Customer</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customers.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $customer->first_name) }}" required>
            </div>

            <div class="mb-3">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $customer->last_name) }}" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}" required>
            </div>

            <div class="mb-3">
                <label>Gender</label>
                <select name="gender" class="form-control" required>
                    <option value="M" {{ old('gender', $customer->gender) == 'M' ? 'selected' : '' }}>Male</option>
                    <option value="F" {{ old('gender', $customer->gender) == 'F' ? 'selected' : '' }}>Female</option>
                </select>
            </div>

            <div class="mb-3">
                <label>State</label>
                <input type="text" name="state" class="form-control" value="{{ old('state', $customer->state) }}">
            </div>

            <div class="mb-3">
                <label>City</label>
                <input type="text" name="city" class="form-control" value="{{ old('city', $customer->city) }}">
            </div>

            <div class="mb-3">
                <label>Street</label>
                <input type="text" name="street" class="form-control" value="{{ old('street', $customer->street) }}">
            </div>

            <div class="mb-3">
                <label>Score</label>
                <input type="number" name="score" class="form-control" value="{{ old('score', $customer->score) }}">
            </div>

            <div class="mb-3">
                <label>Phone Numbers</label>
                <div id="phone-numbers">
                    @php
                        $phones = old('phoneNumbers', $customer->phoneNumbers->pluck('phone_num')->toArray());
                    @endphp
                    @foreach($phones as $phone)
                        <input type="text" name="phoneNumbers[]" class="mb-2 form-control" value="{{ $phone }}">
                    @endforeach
                </div>
                <button type="button" class="btn btn-sm btn-secondary" onclick="addPhoneNumber()">Add Another Number</button>
            </div>
            

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script>
        function addPhoneNumber() {
            const container = document.getElementById('phone-numbers');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'phoneNumbers[]';
            input.className = 'form-control mb-2';
            input.required = true;
            container.appendChild(input);
        }
    </script>

@endsection
