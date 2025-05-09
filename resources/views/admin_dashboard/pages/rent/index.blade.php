{{-- resources/views/admin_dashboard/pages/rent/index.blade.php --}}

@extends('admin_dashboard.layout.pages-layout')

@section('pagetitle', 'Rental Requests')
@section('content')
<div class="container">
    <h2 class="mb-4">Rental Request List</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif



    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Item</th>
                    <th>Customer</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rents as $rent)
                <tr>
                    <td class="text-center">{{ $rent->id }}</td>
                    <td>{{ $rent->item_name }}</td>
                    <td>{{ $rent->first_name }} {{ $rent->last_name }}</td>
                    <td>{{ $rent->start_date }}</td>
                    <td>{{ $rent->end_date }}</td>
                    <td>{{ $rent->delivery_address }}</td>
                    <td class="text-capitalize text-center">
                        <span class="badge bg-{{ $rent->rental_status == 'approved' ? 'success' : ($rent->rental_status == 'rejected' ? 'danger' : 'warning') }}">
                            {{ $rent->rental_status }}
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('rental.edit', $rent->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('rental.destroy', $rent->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">No rental requests found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
