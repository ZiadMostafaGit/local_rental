@extends('admin_dashboard.layout.pages-layout')

@section('pagetitle', 'Dashboard')
@section('content')
<div class="container">
    <h2 class="mb-4">Add New Lender</h2>

    <form action="{{ route('lenders.store') }}" method="POST">
        @csrf

        {{-- First Name --}}
        <div class="mb-3 form-group">
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
        </div>

        {{-- Last Name --}}
        <div class="mb-3 form-group">
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
        </div>

        {{-- Email --}}
        <div class="mb-3 form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

         {{-- Password --}}
         <div class="mb-3 form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" value="{{ old('password') }}" required>
        </div>

        {{-- Gender --}}
        <div class="mb-3 form-group">
            <label for="gender">Gender</label>
            <select name="gender" class="form-control" required>
                <option value="">Select Gender</option>
                <option value="M" {{ old('gender') === 'M' ? 'selected' : '' }}>Male</option>
                <option value="F" {{ old('gender') === 'F' ? 'selected' : '' }}>Female</option>
            </select>
        </div>

        {{-- Address --}}
        <div class="mb-3 form-group">
            <label for="state">State</label>
            <input type="text" name="state" class="form-control" value="{{ old('state') }}">
        </div>

        <div class="mb-3 form-group">
            <label for="city">City</label>
            <input type="text" name="city" class="form-control" value="{{ old('city') }}">
        </div>

        <div class="mb-3 form-group">
            <label for="street">Street</label>
            <input type="text" name="street" class="form-control" value="{{ old('street') }}">
        </div>

        {{-- Score --}}
        <div class="mb-3 form-group">
            <label for="score">Score</label>
            <input type="number" step="0.1" name="score" class="form-control" value="{{ old('score') }}">
        </div>

        {{-- Phone Numbers --}}
        <div class="mb-3 form-group">
            <label for="phoneNumbers[]">Phone Numbers</label>
            <input type="text" name="phoneNumbers[]" class="mb-2 form-control" placeholder="Enter phone number">
            <div id="additional-phones"></div>
            <button type="button" class="mt-2 btn btn-sm btn-secondary" onclick="addPhoneField()">Add another phone number</button>
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="btn btn-success">Add</button>
        <a href="{{ route('lenders.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
    function addPhoneField() {
        const container = document.getElementById('additional-phones');
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'phoneNumbers[]';
        input.className = 'form-control mb-2';
        input.placeholder = 'Additional phone number';
        container.appendChild(input);
    }
</script>
@endsection
