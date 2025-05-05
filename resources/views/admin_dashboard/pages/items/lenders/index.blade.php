@extends('admin_dashboard.layout.pages-layout')

@section('pagetitle', 'Dashboard')
@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">lender Table</h1>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Score</th>
                <th>gender</th>
                <th>state</th>
                <th>city</th>
                <th>street</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lenders as $lender)
                <tr>
                    <td>{{ $lender->first_name }} {{ $lender->last_name }}</td>
                    <td>{{ $lender->email }}</td>
                    <td>{{ $lender->score }}</td>
                    <td>{{ $lender->gender }}</td>
                    <td>{{ $lender->state }}</td>
                    <td>{{ $lender->city }}</td>
                    <td>{{ $lender->street }}</td>
                    <td>
                        <a href="{{ route('admin.items.pending') }}" class="btn btn-primary">Requests</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
