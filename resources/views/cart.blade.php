@extends('layout')

@section('title', 'Shopping Cart')

@section('extra-css')

@endsection

@section('content')

    <div class="breadcrumbs">
        <div class="container">
            <a href="#">Home</a>
            <i class="fa fa-chevron-right breadcrumb-separator"></i>
            <span>Shopping Cart</span>
{{--            <form action="" method="GET" class="search-form">--}}
{{--                <i class="fa fa-search search-icon"></i>--}}
{{--                <input type="text" name="query" id="query" class="search-box" placeholder="Search for product">--}}
{{--            </form>--}}
        </div>
    </div> <!-- end breadcrumbs -->


    <div class="cart-section container">
        <div>

            @if (session()->has('success_message'))
                <div class="alert alert-success">
                    {{ session()->get('success_message') }}
                </div>
            @endif

            @if(count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
                @if(Cart::count()>0)

            <h2>{{Cart::count()}} item(s) in Shopping Cart</h2>

            <div class="cart-table">
                @foreach(Cart::content() as $item)
                <div class="cart-table-row">
                    <div class="cart-table-row-left">
                        <a href="{{route('product.show',$item->model->slug)}}"><img src="{{ asset('storage/'.$item->model->image) }}" alt="item" class="cart-table-img"></a>
                        <div class="cart-item-details">
                            <div class="cart-table-item"><a href="{{route('product.show',$item->model->slug)}}">{{$item->model->name}}</a></div>
                            <div class="cart-table-description">{{$item->model->details}}</div>
                        </div>
                    </div>


                    <div class="cart-table-row-right">
                        <div class="cart-table-actions">
{{--                            <a href="#">Remove</a> <br>--}}
                            <form action="{{route('cart.destroy',$item->rowId)}}" method="POST">
                                {{csrf_field()}}
                                {{method_field('DELETE')}}
                                <button type="submit" class="cart-options">Remove</button>


                            </form>

                            <form action="{{route('cart.switchToSaveForLater',$item->rowId)}}" method="POST">
                                {{csrf_field()}}
                                <button type="submit" class="cart-options">Save For Later</button>


                            </form>

                        </div>
                        <div>
                            <select class="quantity" data-id="{{$item->rowId}}">
                                @for($i=1;$i< 5+1;$i++)
                                <option {{$item->qty==$i? 'selected':''}}>{{$i}}</option>
{{--                                <option {{$item->qty==2? 'selected':''}}>2</option>--}}
{{--                                <option {{$item->qty==3? 'selected':''}}>3</option>--}}
{{--                                <option {{$item->qty==4? 'selected':''}}>4</option>--}}
{{--                                <option {{$item->qty==5? 'selected':''}}>5</option>--}}
                                @endfor
                            </select>
                        </div>
                        <div>${{presentPrice($item->subtotal)}}</div>
                    </div>
                </div> <!-- end cart-table-row -->

                @endforeach




            </div> <!-- end cart-table -->




                    @if(! session()->has('coupon'))
                        <a href="#" >Have a Code?</a>
                            <form action="{{route('coupon.store')}}" method="POST">
                                {{csrf_field()}}
                                <input type="text" id="coupon_code" name="coupon_code"style="width:500px;height: 50px;margin-bottom: 30px;">
                                <button type="submit" class="button button-plain">Apply</button>
                            </form>
                     @endif






            <div class="cart-totals">



                <div class="cart-totals-left">
                    Shipping is free because we’re awesome like that. Also because that’s additional stuff I don’t feel like figuring out :).
                </div>

                <div class="cart-totals-right">
                    <div>
                        Subtotal <br>

                @if(session()->has('coupon'))
                    Code ({{session()->get('coupon')['name']}})
                    <form action="{{route('coupon.destroy')}}" method="POST" style="display: inline">
                        {{csrf_field()}}
                        {{method_field('delete')}}
                        <button type="submit">Remove</button>
                    </form>
                    <br>

                    <hr>
                    New Subtotal <br>
                @endif
                        Tax (13%) <br>
                        <span class="cart-totals-total">Total</span>
                    </div>
                    <div class="cart-totals-subtotal">

                    ${{presentPrice(Cart::subtotal())}} <br>
                    @if(session()->has('coupon'))
                        -  {{presentPrice($discount) }}<br>
                        <hr>
                        {{presentPrice($newSubtotal) }} <br>
                    @endif
                        ${{presentPrice($newTax)}} <br>
                        <span class="cart-totals-total">${{presentPrice($newTotal)}}</span>
                    </div>
                </div>
            </div> <!-- end cart-totals -->



{{--                    <div class="checkout-totals">--}}
{{--                        <div class="checkout-totals-left">--}}
{{--                            Subtotal <br>--}}
{{--                            @if(session()->has('coupon'))--}}
{{--                                Discount ({{session()->get('coupon')['name']}}) :--}}
{{--                                <form action="{{route('coupon.destroy')}}" method="POST" style="display: inline">--}}
{{--                                    {{csrf_field()}}--}}
{{--                                    {{method_field('delete')}}--}}
{{--                                    <button type="submit">Remove</button>--}}
{{--                                </form>--}}
{{--                                <br>--}}

{{--                                <hr>--}}
{{--                                New Subtotal <br>--}}
{{--                            @endif--}}
{{--                            Tax <br>--}}
{{--                            <span class="checkout-totals-total">Total</span>--}}

{{--                        </div>--}}


{{--                        <div class="checkout-totals-right">--}}
{{--                            ${{presentPrice(Cart::subtotal())}} <br>--}}
{{--                            @if(session()->has('coupon'))--}}
{{--                                -  {{presentPrice($discount) }}<br>--}}
{{--                                <hr>--}}
{{--                                {{presentPrice($newSubtotal) }} <br>--}}
{{--                            @endif--}}
{{--                            ${{presentPrice($newTax)}} <br>--}}
{{--                            <span class="checkout-totals-total">${{presentPrice($newTotal)}}</span>--}}

{{--                        </div>--}}


{{--                    </div> <!-- end checkout-totals -->--}}


                    <div class="cart-buttons">
                <a href="{{route('product.index')}}" class="button">Continue Shopping</a>
                <a href="{{route('checkout.index')}}" class="button-primary">Proceed to Checkout</a>
            </div>

            @else
               <h3>No items in Cart !</h3>
                    <div class="spacer"></div>
                    <a href="{{route('product.index')}}" class="button">Continue Shopping</a>
                    <div class="spacer"></div>

                @endif

                @if(Cart::instance('saveForLater')->count()>0)

                    <h2>{{Cart::instance('saveForLater')->count()}} item(s) in Saved For Later</h2>


            <div class="saved-for-later cart-table">

                @foreach(Cart::instance('saveForLater')->content() as $item)
                <div class="cart-table-row">
                    <div class="cart-table-row-left">
                        <a href="{{route('product.show',$item->model->slug)}}"><img src="/img/macbook-pro.png" alt="item" class="cart-table-img"></a>
                        <div class="cart-item-details">
                            <div class="cart-table-item"><a href="{{route('product.show',$item->model->slug)}}">{{$item->model->name}}</a></div>
                            <div class="cart-table-description">{{$item->model->details}}</div>
                        </div>
                    </div>
                    <div class="cart-table-row-right">
                        <div class="cart-table-actions">
                            <form action="{{route('saveForLater.destroy',$item->rowId)}}" method="POST">
                                {{csrf_field()}}
                                {{method_field('DELETE')}}
                                <button type="submit" class="cart-options">Remove</button>


                            </form>

                            <form action="{{route('SaveForLater.switchToCard',$item->rowId)}}" method="POST">
                                {{csrf_field()}}
                                <button type="submit" class="cart-options">Move to Cart</button>


                            </form>                        </div>

                        <div>${{$item->model->presentPrice()}}</div>
                    </div>
                </div> <!-- end cart-table-row -->

                @endforeach
            </div> <!-- end saved-for-later -->

                @else
                    <h3>You have no items Saved For Later !</h3>


            @endif

        </div>

    </div> <!-- end cart-section -->

    @include('partials.might-like')


@endsection


@section('extra-js')
    <script src="{{asset('js/app.js')}}"></script>
    <script>
    (function(){
    const classname = document.querySelectorAll('.quantity')

    //node form to array form
        Array.from(classname).forEach(function(element){

            element.addEventListener('change',function(){
                const id =element.getAttribute('data-id')
                axios.patch(`cart/${id}`,{
                    quantity: this.value,

                })
                    .then(function (response) {
                        // console.log(response);
                        window.location.href='{{route('cart.index')}}'
                    })
                    .catch(function (error) {
                        console.log(error);
                        window.location.href='{{route('cart.index')}}'

                    });            })
        })
    })();

    </script>

@endsection
