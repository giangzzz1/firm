@extends('LayoutClients.master')

@section('title')
    Shop
@endsection

@section('content_client')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0">
                    <a href="{{ route('index') }}">Home</a>
                    <span class="mx-2 mb-0">/</span>
                    <strong class="text-black">Shop</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-9 order-2">
                    <div class="row">
                        <div class="col-md-12 mb-5">
                            <div class="float-md-left mb-4">
                                <h2 class="text-black h5">Shop All</h2>
                            </div>
                            <div class="d-flex">
                                <div class="dropdown mr-1 ml-md-auto">
                                    <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                        id="dropdownMenuOffset" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        Latest
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuOffset">
                                        <a class="dropdown-item" href="{{ route('shop.index') }}">
                                            All
                                        </a>
                                        @foreach ($categories as $category)
                                            <a class="dropdown-item"
                                                href="{{ route('shop.index', ['category' => $category->id]) }}">
                                                {{ $category->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                        id="dropdownMenuReference" data-toggle="dropdown">Reference</button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuReference">
                                        <a class="dropdown-item"
                                            href="{{ route('shop.index', ['sort_by' => 'relevance']) }}">
                                            Relevance
                                        </a>
                                        <a class="dropdown-item" href="{{ route('shop.index', ['sort_by' => 'name_az']) }}">
                                            Name, A to Z
                                        </a>
                                        <a class="dropdown-item" href="{{ route('shop.index', ['sort_by' => 'name_za']) }}">
                                            Name, Z to A
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item"
                                            href="{{ route('shop.index', ['sort_by' => 'price_low_high']) }}">
                                            Price, low to high
                                        </a>
                                        <a class="dropdown-item"
                                            href="{{ route('shop.index', ['sort_by' => 'price_high_low']) }}">
                                            Price, high to low
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5">
                        @foreach ($tickets as $ticket)
                            <div class="col-sm-6 col-lg-4 mb-4" data-aos="fade-up">
                                <div class="block-4 text-center border">
                                    <figure class="block-4-image">
                                        <a href="{{ route('ticket.show', $ticket->id) }}">
                                            <img src="{{ asset('storage/' . $ticket->image) }}" alt="Image placeholder"
                                                class="img-fluid">
                                        </a>
                                    </figure>
                                    <div class="block-4-text p-4">
                                        <h3> <a href="{{ route('ticket.show', $ticket->id) }}">
                                                {{ $ticket->name }}</a></h3>
                                        <p class="ticket-price" style="color: red">
                                            {{ number_format($ticket->price, 0, ',', '.') }} USD</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="pagination">
                        {{ $tickets->links() }}
                    </div>
                </div>

                <div class="col-md-3 order-1 mb-5 mb-md-0">
                    <div class="border p-4 rounded mb-4">
                        <h3 class="mb-3 h6 text-uppercase text-black d-block">Categories</h3>
                        <ul class="list-unstyled mb-0">
                            @foreach ($categories as $category)
                                <li class="mb-1">
                                    <a href="#" class="d-flex">
                                        <span>{{ $category->name }}</span>
                                        <span class="text-black ml-auto">({{ $category->tickets_count }})</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <form method="GET" action="{{ route('shop.index') }}" id="filter-form">
                        <div class="border p-4 rounded mb-4">
                            <h3 class="mb-3 h6 text-uppercase text-black d-block">Filter by Price</h3>

                            <div id="slider-range" class="border-primary"></div>

                            <!-- Hiển thị giá trị đã chọn từ slider -->
                            <input type="text" id="amount" class="form-control border-0 pl-0 bg-white" disabled />

                            <!-- Các trường ẩn để gửi giá trị giá thấp và giá cao -->
                            <input type="hidden" name="price_min" id="price_min">
                            <input type="hidden" name="price_max" id="price_max">

                            <button type="submit" class="btn btn-primary mt-3">Apply Filter</button>
                        </div>
                    </form>



                </div>
            </div>

        </div>
    </div>
@endsection
