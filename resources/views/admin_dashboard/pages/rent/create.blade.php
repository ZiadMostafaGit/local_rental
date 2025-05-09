
@extends('admin_dashboard.layout.pages-layout')

@section('pagetitle', 'Rental Requests')
@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Create New Rental Request</h2>

    <form action="{{ route('rental.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="customer_id" class="form-label">Customer</label>
            <select name="customer_id" class="form-select" required>
                <option disabled selected>-- Choose Customer --</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->full_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="item_id" class="form-label">Item</label>
            <select name="item_id" class="form-select" required>
                <option disabled selected>-- Choose Item --</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Delivery Address</label>
            <input type="text" name="delivery_address" class="form-control" maxlength="100">
        </div>

        <div class="mb-3">
            <label class="form-label">Payment Token</label>
            <input type="text" name="payment_token" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Rental Status</label>
            <select name="rental_status" class="form-select" required>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>
@endsection
