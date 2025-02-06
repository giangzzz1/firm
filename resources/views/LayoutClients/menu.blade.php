<nav class="site-navigation text-right text-md-center" role="navigation">
    <div class="container">
        <ul class="site-menu js-clone-nav d-none d-md-block">
            <li><a href="{{ route('index') }}">Home</a></li>
            <li class="has-children active">
                <a href="{{ route('index') }}">Category</a>
                <ul class="dropdown">
                    @foreach ($categories as $category)
                        <a class="dropdown-item" href="{{ route('shop.index', ['category' => $category->id]) }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </ul>
            </li>
            <li><a href="{{ route('shop.index') }}">Shop</a></li>
            <li><a href="{{ route('about') }}">About</a></li>
            <li><a href="{{ route('contact') }}">Contact</a></li>

        </ul>
    </div>
</nav>
