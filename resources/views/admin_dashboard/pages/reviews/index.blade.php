@extends('admin_dashboard.layout.pages-layout')

@section('pagetitle', 'Dashboard')
@section('content')
<div class="container mt-4">
    <h2 class="mb-4">All Reviews</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Table -->
    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>ID</th>
                <th>Item</th>
                <th>Customer</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reviews as $review)
            <tr>
                <td class="text-center">{{ $review->id }}</td>
                <td>{{ $review->item->title }}</td>
                <td>{{ $review->customer->first_name }} {{ $review->customer->last_name }}</td>
                <td class="text-center">{{ $review->rating }}</td>
                <td>{{ Str::limit($review->comment, 50) }}</td> <!-- Limit the comment length to avoid overflow -->
                <td class="text-center">
                    <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-sm btn-warning me-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
