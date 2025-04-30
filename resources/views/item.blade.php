@foreach ($itemImages as $image)
    <div class="image-container">
        <img src="{{ asset('storage/item_images/' . $image->id . '.png') }}" alt="Image" />
    </div>
@endforeach


<form action="{{ route('item.images.store', $item->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="images">Upload Images</label>
    <input type="file" name="images[]" multiple>
    <button type="submit">Upload</button>
</form>
