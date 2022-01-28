@extends('layout')

@section('title', 'Search Results')

@section('extra-css')

<link rel="stylesheet" href="{{asset('css/algolia.css')}}">
@endsection

@section('content')



    <div class="breadcrumbs">
        <div class="container">
            <a href="{{route('main')}}">Home</a>
            <i class="fa fa-chevron-right breadcrumb-separator"></i>
         @include('partials.search')
        </div>
    </div> <!-- end breadcrumbs -->

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


    <div class="search-results-container container">
        <h1>Search Results</h1>
{{--        count ==> normal
            total ==>paination--}}
        <p class="search-results-count">{{ $products->count() }} result(s) for '{{ request()->input('query') }}'</p>

        @if ($products->count() > 0)
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Details</th>
                    <th>Description</th>
                    <th>Price</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($products as $product)
                    <tr>
                        <th><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></th>
                        <td>{{ $product->details }}</td>
                        <td>{{  \Illuminate\Support\Str::limit($product->description, 80) }}</td>
                        <td>{{ $product->presentPrice() }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

{{--            {{ $products->appends(request()->input())->links() }}--}}
        @endif
    </div> <!-- end search-results-container -->




@endsection

@section('extra-js')
    <!-- Include AlgoliaSearch JS Client and autocomplete.js library -->
    <script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
    <script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>
    <script src="{{ asset('js/algolia.js') }}"></script>

@endsection
