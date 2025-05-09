@extends('admin_dashboard.layout.pages-layout')

@section('pagetitle', 'Dashboard')
@section('content')
<div class="container">
    <h1 class="mb-4">Add New Customer</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customers.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
        </div>

        <div class="mb-3">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label>Gender</label>
            <select name="gender" class="form-control" required>
                <option value="">Select</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label>State</label>
            <input type="text" name="state" class="form-control" value="{{ old('state') }}">
        </div>

        <div class="mb-3">
            <label>City</label>
            <input type="text" name="city" class="form-control" value="{{ old('city') }}">
        </div>

        <div class="mb-3">
            <label>Street</label>
            <input type="text" name="street" class="form-control" value="{{ old('street') }}">
        </div>

        <div class="mb-3">
            <label>Score</label>
            <input type="number" name="score" class="form-control" value="{{ old('score') }}">
        </div>

        <div class="mb-3">
            <label>Phone Numbers</label>
            <div id="phone-numbers">
                @php
                    $phones = old('phoneNumbers', ['']);
                @endphp
                @foreach($phones as $phone)
                    <input type="text" name="phoneNumbers[]" class="mb-2 form-control" value="{{ $phone }}" required>
                @endforeach
            </div>
            <button type="button" class="btn btn-sm btn-secondary" onclick="addPhoneNumber()">Add Another Number</button>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
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
