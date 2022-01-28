<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Mail\OrderPlaced;
use App\Models\Order;
use App\Models\OrderProduct;
use Cartalyst\Stripe\Exception\CardErrorException;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class CheckoutController extends Controller
{

    public function index()
    {

        if(auth()->user() && request()->is('guestCheckout')){
            return redirect()->route('checkout.index');
        }



        return view('checkout')->with(['discount'=>getNumbers()->get('discount'),
                                            'newTax' =>getNumbers()->get('newTax'),
                                            'newSubtotal' =>getNumbers()->get('newSubtotal'),
                                            'newTotal' =>getNumbers()->get('newTotal')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public function store(CheckoutRequest $request)
    {
        $contents =Cart::content()->map(function ($item){
            return $item->model->slug.', '.$item->qty;})->values()->toJson();
        try{
            $charge = Stripe::charges()->create([
                'amount' => getNumbers()->get('newTotal')/100,
                'currency' => 'CAD',
                'source' => $request->stripeToken,
                'description'=>'Order',
                'receipt_email'=>$request->email,
                'metadata'=>[
                    'contents'=>$contents,
                    'quantity'=>Cart::instance('default')->count(),
                    'discount'=>collect(session()->get('coupon'))->toJson(),

            ],
            ]);
            $order = $this->addToOrdersTables($request, null);
            Mail::send(new OrderPlaced($order));

            foreach (Cart::content() as $item)
                OrderProduct::create([
                    'order_id'=>$order->id,
                    'product_id'=>$item->model->id,
                    'quantity' => $item->qty,
                ]);


            Cart::instance('default')->destroy();
            session()->forget('coupon');
            return redirect()->route('confirmation.index')->with('success_message','Thank you ! your payment has been successfully accepted');




        }

        catch(CardErrorException $e){
            $this->addToOrdersTables($request, $e->getMessage());

            return back()->withErrors('Error!'.$e->getMessage());

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    protected function addToOrdersTables($request, $error)
    {
        $order = Order::create([
            'user_id' => auth()->user() ? auth()->user()->id : null,
            'billing_email' => $request->email,
            'billing_name' => $request->name,
            'billing_address' => $request->address,
            'billing_city' => $request->city,
            'billing_province' => $request->province,
            'billing_postalcode' => $request->postalcode,
            'billing_phone' => $request->phone,
            'billing_name_on_card' => $request->name_on_card,
            'billing_discount' => getNumbers()->get('discount'),
            'billing_discount_code' => getNumbers()->get('code'),
            'billing_subtotal' => getNumbers()->get('newSubtotal'),
            'billing_tax' => getNumbers()->get('newTax'),
            'billing_total' => getNumbers()->get('newTotal'),
            'error' => $error,
        ]);

        foreach (Cart::content() as $item) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $item->model->id,
                'quantity' => $item->qty,
            ]);
        }

        return $order;
    }
}
