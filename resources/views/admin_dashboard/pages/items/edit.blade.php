@extends('admin_dashboard.layout.pages-layout')
@section('pagetitle', 'Dashboard')
@section('content')

    <div class="container mt-4">
        <h2>Edit Item</h2>
        <form action="{{ route('items.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Title --}}
            <div class="form-group mb-3">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}" required>
            </div>

            {{-- Description --}}
            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" rows="4" required>{{ old('description', $item->description) }}</textarea>
            </div>

            {{-- Price --}}
            <div class="form-group mb-3">
                <label for="price">Price</label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $item->price) }}" required step="0.01">
            </div>

            {{-- Category --}}
            <div class="form-group mb-3">
                <label for="category_id">Category</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $item->categories->first()->id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status (optional) --}}
            <div class="form-group mb-3">
                <label for="item_status">Item Status</label>
                <input type="text" name="item_status" class="form-control" value="{{ old('item_status', $item->item_status) }}">
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('items.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    @endsection
