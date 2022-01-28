<header>
    <div class="top-nav container">
        <div class="logo"><a href="/">Laravel Ecommerce</a></div>
{{--        @if (! request()->is('checkout'))--}}
        <ul>
            <li><a href="{{route('product.index')}}">Shop</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Blog</a></li>
            <li><a href="{{route('cart.index')}}">Cart <span class="cart-count">
             @if(Cart::instance('default')->count()>0)
             <span>{{Cart::instance('default')->count()}}</span></span></a></li>
            @endif
            @guest
            <li><a href="{{route('login')}}">Login</a></li>
            <li><a href="{{route('register')}}">Sign Up</a></li>
            @else
        <li>
        <a class="dropdown-item" href="{{ route('logout') }}"
           onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
        </a>
        </li>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
            @endguest

        </ul>
{{--        @endif--}}
    </div> <!-- end top-nav -->
</header>
