@extends('LayoutClients.master')

@section('title')
    {{ $ticket->name }}
@endsection

@section('content_client')
    <div class="container ticket-detail-container">
        <form action="{{ route('cart.add') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-4">
                    <figure class="ticket-image">
                        <img style="max-width: 100%" src="{{ asset('storage/' . $ticket->image) }}" alt="{{ $ticket->name }}" class="img-fluid">
                    </figure>
                </div>
                <div class="col-lg-8">
                    <h2 class="ticket-title">{{ $ticket->name }}</h2>
                    <p class="ticket-description">{{ $ticket->description }}</p>
                    <p class="ticket-time">
                        Thời gian: {{ \Carbon\Carbon::parse($ticket->startday)->format('d/m/Y H:i') }} -
                        {{ \Carbon\Carbon::parse($ticket->enday)->format('d/m/Y H:i') }}
                    </p>
                    <p class="ticket-price" style="color: red">{{ number_format($ticket->price, 0, ',', '.') }} USD</p>
                    <p>Category: {{ $ticket->category->name }}</p>

                    <!-- Thêm vào giỏ hàng -->
                    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                </div>
            </div>
        </form>
    </div>
@endsection
