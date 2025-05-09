@extends('admin_dashboard.layout.pages-layout')

@section('pagetitle', 'Dashboard')

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

    <h2>pending items  </h2>

    @if ($items->isEmpty())
        <p> no items pending .</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>name</th>
                    <th>lender</th>
                    <th>actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->price }}</td>
                        <td>{{ $item->lender->first_name  }} {{   $item->lender->last_name}}</td>
                        <td>
                            <form action="{{ route('admin.items.approve', $item->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Accept</button>
                            </form>

                            <form action="{{ route('admin.items.reject', $item->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
