@extends('admin_dashboard.layout.pages-layout')
@section('pagetitle', 'Dashboard')
@section('content')

<div class="container mt-4">
    <h2>Add New Item</h2>

    <form method="POST" action="{{ route('items.store') }}">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Item Title</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Enter item title" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Item Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Optional description..."></textarea>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price (EGP)</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
        </div>

        <div class="mb-3">
            <label for="item_status" class="form-label">Status</label>
            <select class="form-select" id="item_status" name="item_status" required>
                <option value="">-- Select Status --</option>
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
            </select>
        </div>

        @if(isset($lenders))
        <div class="mb-3">
            <label for="lender_id" class="form-label">Lender</label>
            <select class="form-select" id="lender_id" name="lender_id" required>
                <option value="">-- Select Lender --</option>
                @foreach($lenders as $lender)
                    <option value="{{ $lender->id }}">{{ $lender->first_name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-select" id="category_id" name="category_id" required>
                <option value="">-- Select Category --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Add Item</button>
    </form>
</div>

@endsection
