<div class="might-like-section">
    <div class="container">
        <h2>You might also like...</h2>
        <div class="might-like-grid">
            @foreach($mightAlsoLike as $product)
            <a href="{{route('product.show',$product->slug)}}" class="might-like-product">
                <img src="{{ asset('storage/'.$product->image) }}" alt="product">
                <div class="might-like-product-name">{{$product->name}}</div>
                <div class="might-like-product-price">{{$product->presentPrice()}}</div>
            </a>
            @endforeach

        </div>
    </div>
</div>
