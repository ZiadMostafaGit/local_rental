@extends('admin_dashboard.layout.pages-layout')

@section('pagetitle', 'Dashboard')
@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Customers</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>Full Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Address</th>
                <th>Score</th>
                <th>Phone Numbers</th>
                <th>Actions</th>
                <th>Send Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ ucfirst($customer->gender) }}</td>
                    <td>{{ $customer->state }} - {{ $customer->city }} - {{ $customer->street }}</td>
                    <td>{{ $customer->score }}</td>
                    <td>
                        @foreach($customer->phoneNumbers as $phone)
                            <div>{{ $phone->phone_num }}</div>
                        @endforeach
                    </td>
                    <td class="text-center">
                        <!-- Edit Button -->
                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-warning me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        <!-- Delete Button -->
                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                    <td class="text-center">
                        <!-- Send Email Button -->
                        <a href="{{ route('customer.send.mail',$customer->id) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-paper-plane"></i> Send Email
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
