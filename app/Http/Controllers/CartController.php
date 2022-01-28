<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{

    public function index()
    {
        $mightAlsoLike = Product::mightAlsoLike()->get();

        return view('cart')->with(['discount'=>getNumbers()->get('discount'),
                'newTax' =>getNumbers()->get('newTax'),
                'newSubtotal' =>getNumbers()->get('newSubtotal'),
                'newTotal' =>getNumbers()->get('newTotal'),
                'mightAlsoLike'=>$mightAlsoLike]);

    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {

        $duplicates = Cart::search(function($cartItem)use($request){
            return $cartItem->id === $request->id;
        });

        if($duplicates->isNotEmpty()){
            return redirect()->route('cart.index')->with('success_message','Item is already in your cart!');

        }
        Cart::add($request->id,$request->name,1,$request->price)
             ->associate('App\Models\Product');

        return redirect()->route('cart.index')
                         ->with('success_message','Item was added to your cart!');
    }


    public function switchToSaveForLater($id){
        $item = Cart::get($id);
        Cart::remove($id);
        $duplicates =  Cart::instance('saveForLater')->search(function($cartItem,$rowId)use($id){
            return $rowId === $id;
        });

        if($duplicates->isNotEmpty()){
            return redirect()->route('cart.index')->with('success_message','Item is already Saved For Later!');

        }

        Cart::instance('saveForLater')->add($item->id , $item->name , 1 , $item->price)
                                      ->associate('App\Models\Product');


        return redirect()->route('cart.index')
            ->with('success_message','Item has been Saved for Later !');

    }


    public function empty(){
        Cart::destroy();
    }

    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {

       $validator = Validator::make($request->all(),[
           'quantity'=>'required|numeric|between:1,5'
       ]);

       if($validator->fails()){

           session()->flash('errors',collect(['Quantity must be between 1 and 5']));
           return response()->json(['success'=>false],400);
       }
       Cart::update($id,$request->quantity);

       session()->flash('success_message','Quantity was updated successfully!');
       return response()->json(['success'=>true]);

    }


    public function destroy($id)
    {
        Cart::remove($id);
        return back()->with('success_message','Item has been removed');


    }


}
