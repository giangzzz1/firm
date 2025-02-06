@extends('LayoutAdmin.master')

@section('title')
    Danh sách vé xem phim
@endsection

@section('content_admin')
    <div class="container">
        <h2 class="text-center">Danh sách vé xem phim</h2>
        <a href="{{ route('tickets.create') }}" class="btn btn-success mb-3">Thêm mới</a>


        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category</th>
                        <th>Name</th>
                        <th>Avatar</th>
                        <th>Start Day</th>
                        <th>End Day</th>
                        <th>Quantity</th>
                        <th>Sell</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Organizer</th>
                        <th>Location</th>
                        <th>Is Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td>{{ $ticket->category->name ?? 'No Category' }}</td>
                            <td>{{ $ticket->name }}</td>
                            <td><img src="{{ asset('storage/' . $ticket->image) }}" alt="{{ $ticket->name }}" width="50"></td>
                            <td>{{ \Carbon\Carbon::parse($ticket->startday)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($ticket->enday)->format('d/m/Y') }}</td>
        
                            <td>{{ $ticket->quantity }}</td>
                            <td>{{ $ticket->sell_quantity }}</td>
                            <td>
                                {{ number_format($ticket->price) }} USD
                                @if ($solPrice)
                                    ≈ {{ number_format($ticket->price / $solPrice, 4) }} SOL
                                @endif
                            </td>
                            <td>{{ $ticket->description }}</td>
                            <td>{{ $ticket->nguoitochuc }}</td>
                            <td>{{ $ticket->address }}</td>
                            <td>
                                <span class="badge {{ $ticket->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $ticket->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
@endsection
