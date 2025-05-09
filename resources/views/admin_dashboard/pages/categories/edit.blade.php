@extends('admin_dashboard.layout.pages-layout')

@section('pagetitle', 'Dashboard')
@section('content')
<h1>Edit Category</h1>

<form action="{{ route('categories.update', $category->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="name">Category Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mt-3 form-group">
        <label for="description">Description</label>
        <textarea name="description" class="form-control">{{ old('description', $category->description) }}</textarea>
    </div>

    <button type="submit" class="mt-3 btn btn-primary">Update</button>
</form>
@endsection
