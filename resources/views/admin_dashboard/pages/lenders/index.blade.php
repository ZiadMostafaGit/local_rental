@extends('admin_dashboard.layout.pages-layout')

@section('pagetitle', 'Dashboard')
@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Lender Table</h1>

        <!-- Table -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Score</th>
                    <th>Gender</th>
                    <th>State</th>
                    <th>City</th>
                    <th>Street</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lenders as $lender)
                    <tr>
                        <td>{{ $lender->first_name }} {{ $lender->last_name }}</td>
                        <td>{{ $lender->email }}</td>
                        <td>{{ $lender->score }}</td>
                        <td>{{ ucfirst($lender->gender) }}</td> <!-- Capitalize gender -->
                        <td>{{ $lender->state }}</td>
                        <td>{{ $lender->city }}</td>
                        <td>{{ $lender->street }}</td>
                        <td class="d-flex align-items-center">
                            <!-- View Requests Button -->
                            <a href="{{ route('admin.items.pending') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-list"></i> Requests
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
