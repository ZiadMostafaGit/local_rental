@extends('admin_dashboard.layout.pages-layout')

@section('pagetitle', 'Dashboard')
@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Items Table</h1>

        <!-- Table -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Lender</th>
                    <th>Categories</th>
                    <th>Images</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ Str::limit($item->description, 50) }}</td> <!-- Limit text for description -->
                        <td>{{ $item->price }}$</td> <!-- Display price with a currency symbol -->
                        <td>
                            <span class="badge {{ $item->item_status == 'available' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($item->item_status) }}
                            </span>
                        </td>
                        <td>{{ $item->lender->first_name }}</td>
                        <td>
                            @foreach ($item->categories as $category)
                                <span class="badge bg-primary">{{ $category->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <!-- Show Images -->
                            @foreach ($item->images as $image)
                                <img src="{{ asset('storage/item_images/' . $image->id . '.png')}}" class="img-thumbnail" width="50" height="50">
                            @endforeach
                        </td>

                        <td class="d-flex align-items-center">
                            <!-- Edit Button -->
                            <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-warning me-2">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </td>

                        <!-- Delete Button -->
                        <td class="d-flex align-items-center">
                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?')" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>

                        <!-- Add to Category Button -->
                        <td class="d-flex align-items-center">
                            <form action="{{ route('items.addCategory', $item->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <select name="category_id" onchange="this.form.submit()" class="form-select form-select-sm ms-2">
                                    <option disabled selected>Add to Category</option>
                                    @foreach ($categories as $category)
                                        @if (!$item->categories->contains($category->id))
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </form>
                        </td>

                        <!-- Add Images Button -->
                        <td class="d-flex align-items-center">
                            <a href="{{ route('item.images.index', $item->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-image"></i> Add Image
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
