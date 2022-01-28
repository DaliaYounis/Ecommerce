@extends('layout')

@section('title', 'Products')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('css/algolia.css')}}">



    <style>

        body > div.products-section.container > div:nth-child(2) > nav > div.hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between > div:nth-child(2) > span > span:nth-child(even) > span > svg{
            width: 20px;
            height: 10px;

        }

        body > div.products-section.container > div:nth-child(2) > nav > div.hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between > div:nth-child(2) > span > span:nth-child(odd) > span > svg{
            width: 20px;
            height: 10px;
        }

        body > div.products-section.container > div:nth-child(2) > nav > div.hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between > div:nth-child(2) > span > a.relative.inline-flex.items-center.px-2.py-2.text-sm.font-medium.text-gray-500.bg-white.border.border-gray-300.rounded-l-md.leading-5.hover\:text-gray-400.focus\:z-10.focus\:outline-none.focus\:border-blue-300.focus\:shadow-outline-blue.active\:bg-gray-100.active\:text-gray-500.transition.ease-in-out.duration-150 > svg{
            width: 20px;
            height: 10px;
        }

        body > div.products-section.container > div:nth-child(2) > nav > div.hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between > div:nth-child(2) > span > span:nth-child(1) > span > svg{
            width: 20px;
            height: 10px;
        }
        body > div.products-section.container > div:nth-child(2) > nav > div.hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between > div:nth-child(2) > span > a.relative.inline-flex.items-center.px-2.py-2.-ml-px.text-sm.font-medium.text-gray-500.bg-white.border.border-gray-300.rounded-r-md.leading-5.hover\:text-gray-400.focus\:z-10.focus\:outline-none.focus\:border-blue-300.focus\:shadow-outline-blue.active\:bg-gray-100.active\:text-gray-500.transition.ease-in-out.duration-150 > svg{
            width: 20px;
            height: 10px;
        }

        .products-header{
            display: flex;
            justify-content: space-between;
        }

        .sidebar li.active{
            font-weight: 500;
        }
    </style>

@endsection

@section('content')


    @if (session()->has('success_message'))
        <div class="alert alert-success">
            {{ session()->get('success_message') }}
        </div>
    @endif

    @if(count($errors) > 0)
        <div class="alert alert-danger" style="margin: 20px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="breadcrumbs">
        <div class="container">
            <a href="{{route('main')}}">Home</a>
            <i class="fa fa-chevron-right breadcrumb-separator"></i>
            <span style="margin-right: 30px;">Shop</span >
            @include('partials.search')

        </div>


    </div> <!-- end breadcrumbs -->





    <div class="products-section container">
        <div class="sidebar">
            <h3>By Category</h3>
            <ul>
                @foreach($categories as $category)
                <li class="{{ setActiveCategory($category->slug) }}"><a href="{{route('product.index',['category'=>$category->slug])}}">{{$category->name}}</a></li>
                @endforeach
            </ul>

        </div> <!-- end sidebar -->
        <div>
            <div class="products-header">
             <h1 class="stylish-heading">{{$categoryName}}</h1>
             <div>
                <strong style="font-weight: bold;">Price</strong>
                <a href="{{route('product.index',['category'=>request()->category,'sort'=>'low_high'])}}">Low to High</a>
                <a href="{{route('product.index',['category'=>request()->category,'sort'=>'high_low'])}}">High to Low</a>
             </div>
        </div>
            <div class="products text-center">
               @forelse($products as $product)
                <div class="product">
                  <a href="{{route('product.show',$product->slug)}}"><img src="{{ asset('storage/'.$product->image) }}" alt="product"></a>
                  <a href="{{route('product.show',$product->slug)}}"><div class="product-name">{{$product->name}}</div></a>
                  <div class="product-price">{{$product->presentPrice()}}</div>
                </div>

                @empty
                    <div style="text-align: left;">No items Found</div>
                @endforelse

            </div> <!-- end products -->

            <div class="spacer"></div>

            {{ $products->appends(request()->input())->links() }}

        </div>

    </div>


@endsection
@section('extra-js')
    <!-- Include AlgoliaSearch JS Client and autocomplete.js library -->
    <script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
    <script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>
    <script src="{{ asset('js/algolia.js') }}"></script>

@endsection
