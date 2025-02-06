@extends('LayoutAdmin.master')

@section('title')
    Edit Ticket
@endsection

@section('content_admin')
<div class="container">
    <h2 class="text-center">Edit Ticket</h2>
    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group mt-3">
            <label>Category</label>
            <select name="category_id" class="form-control">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $ticket->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mt-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $ticket->name }}" maxlength="50" required>
        </div>

        <div class="form-group mt-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control" id="image">
            <img id="preview-image" src="{{ asset('storage/' . $ticket->image) }}" alt="Image Preview" style="display: block; margin-top: 10px; width: 150px; height: 100px;" />
        </div>

        <div class="form-group mt-3">
            <label>Start Day</label>
            <input type="date" name="startday" class="form-control" value="{{ \Carbon\Carbon::parse($ticket->startday)->format('Y-m-d') }}" required>
        </div>

        <div class="form-group mt-3">
            <label>End Day</label>
            <input type="date" name="enday" class="form-control" value="{{ \Carbon\Carbon::parse($ticket->enday)->format('Y-m-d') }}" required>
        </div>

        <div class="form-group mt-3">
            <label>Quantity</label>
            <input type="number" name="quantity" class="form-control" value="{{ $ticket->quantity }}" required>
        </div>

        <div class="form-group mt-3">
            <label>Price</label>
            <input type="number" name="price" class="form-control" step="0.01" value="{{ $ticket->price }}" required>
        </div>

        <div class="form-group mt-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="10">{{ $ticket->description }}</textarea>
        </div>

        <div class="form-group mt-3">
            <label>Organizer</label>
            <input type="text" name="nguoitochuc" class="form-control" value="{{ $ticket->nguoitochuc }}">
        </div>

        <div class="form-group mt-3">
            <label>Location</label>
            <input type="text" name="address" class="form-control" value="{{ $ticket->address }}">
        </div>

        <div class="form-group mt-3">
            <label>Is Active</label>
            <input type="checkbox" name="is_active" value="1" {{ $ticket->is_active ? 'checked' : '' }} class="mb-4 ms-3">
        </div>

        <button type="submit" class="btn btn-primary">Update Ticket</button>
        <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

<script>
    // Hiển thị hình ảnh khi chọn file mới
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            const preview = document.getElementById('preview-image');
            preview.src = e.target.result;
            preview.style.display = 'block';
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    });
</script>

@endsection
