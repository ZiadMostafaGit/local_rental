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

    <h2>العناصر المعلقة</h2>

    @if ($items->isEmpty())
        <p>لا توجد عناصر معلقة.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>المُقرض</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->lender->first_name ?? 'غير معروف' }}</td>
                        <td>
                            <form action="{{ route('admin.items.approve', $item->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">موافقة</button>
                            </form>
                            
                            <form action="{{ route('admin.items.reject', $item->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">رفض</button>
                            </form>
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
