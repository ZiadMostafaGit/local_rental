
@extends('admin_dashboard.layout.pages-layout')

@section('pagetitle', 'Rental Requests')
@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Edit Rental Request</h2>

    <form action="{{ route('rental.update', $rent->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Customer</label>
            <select name="customer_id" class="form-select" required>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ $rent->customer_id == $customer->id ? 'selected' : '' }}>
                        {{ $customer->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Item</label>
            <select name="item_id" class="form-select" required>
                @foreach($items as $item)
                    <option value="{{ $item->id }}" {{ $rent->item_id == $item->id ? 'selected' : '' }}>
                        {{ $item->title }}
                    </option>
                @endforeach
            </select>
        </div>

         <div class="mb-3">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $rent->start_date) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $rent->end_date) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Delivery Address</label>
            <input type="text" name="delivery_address" class="form-control" value="{{ $rent->delivery_address }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Payment Token</label>
            <input type="text" name="payment_token" class="form-control" value="{{ $rent->payment_token }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Rental Status</label>
            <select name="rental_status" class="form-select" required>
                <option value="pending" {{ $rent->rental_status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $rent->rental_status == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $rent->rental_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
