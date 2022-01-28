<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {

        $categories = Category::all();
         if(request()->category){
             $products = Product::with('categories')
                        ->whereHas('categories',function($query){
                        $query->where('slug',request()->category);
                                });
             $categoryName =optional(Category::where('slug',request()->category)->first())->name;

         }else{

        $products   = Product::where('featured',true);
        $categoryName = 'Featured';
    }

         if(request()->sort == 'low_high'){ //for second query string
         $products = $products->orderBy('price')->paginate(9);
         }elseif(request()->sort == 'high_low'){
             $products = $products->orderBy('price','desc')->paginate(9);
         }else{
             $products = $products->paginate(9);

         }

        return view('products',compact('products','categories','categoryName'));

    }


    public function create()
    {

    }


    public function store(Request $request)
    {

    }


    public function show($slug)
    {
        $product = Product::where('slug',$slug)->firstorfail();
        $mightAlsoLike = Product::where('slug','!=',$slug)->mightAlsoLike()->get();
      return view('product',compact('product','mightAlsoLike'));
    }


    public function search(Request $request){
        $request->validate([
            'query'=>'required|min:3',
        ]);

        $query = request()->input('query');
        $products = Product::where('name','like',"%$query%")
                             ->orwhere('details','like',"%$query%")
                             ->get();
        return view('search-results',compact('products'));

    }

    public function edit($id)
    {

    }


    public function update(Request $request, $id)
    {

    }


    public function destroy($id)
    {

    }
}
