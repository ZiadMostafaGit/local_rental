@extends('admin_dashboard.layout.pages-layout')

@section('pagetitle', 'Dashboard')
@section('content')
<div class="container">
    <h2 class="mb-4">Add Review</h2>

    <form action="{{ route('reviews.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="customer_id">Customer</label>
            <select name="customer_id" class="form-control" required>
                <option value="">Select Customer</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->full_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="item_id">Item</label>
            <select name="item_id" class="form-control" required>
                <option value="">Select Item</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="rating">Rating</label>
            <input type="number" step="0.1" min="0" max="5" name="rating" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="comment">Comment (optional)</label>
            <textarea name="comment" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit Review</button>
    </form>
</div>
@endsection
