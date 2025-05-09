@extends('admin_dashboard.layout.pages-layout')

@section('pagetitle', 'Dashboard')
@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="container mt-4">
    <h1 class="mb-4">Lender Table</h1>

    <!-- زر عرض الطلبات خارج الجدول -->
    <div class="mb-3">
        <a href="{{ route('admin.items.pending') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-list"></i> View Requests
        </a>
    </div>

    <!-- Table -->
    <table class="table table-striped table-hover table-bordered align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Score</th>
                <th>Gender</th>
                <th>Phone Numbers</th>
                <th>State</th>
                <th>City</th>
                <th>Street</th>
                <th>Edit</th>
                <th>Delete</th>
                <th>Send Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lenders as $lender)
                <tr>
                    <td>{{ $lender->first_name }} {{ $lender->last_name }}</td>
                    <td>{{ $lender->email }}</td>
                    <td>{{ $lender->score }}</td>
                    <td>{{ ucfirst($lender->gender) }}</td>
                    <td>
                        @foreach($lender->phoneNumbers as $phone)
                            <div>{{ $phone->phone_num }}</div>
                        @endforeach
                    </td>
                    <td>{{ $lender->state }}</td>
                    <td>{{ $lender->city }}</td>
                    <td>{{ $lender->street }}</td>
                    <td class="text-center">
                        <!-- Edit Button -->
                        <a href="{{ route('lenders.edit', $lender->id) }}" class="btn btn-sm btn-warning me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </td>

                    <!-- Delete Button -->
                    <td class="text-center">
                        <form action="{{ route('lenders.destroy', $lender->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?')" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>

                    <!-- Send Email Button -->
                    <td class="text-center">
                        <a href="{{ route('lender.send.mail', $lender->id) }}">
                            <button class="btn btn-sm btn-success">
                                <i class="fas fa-paper-plane"></i> Send Email
                            </button>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
