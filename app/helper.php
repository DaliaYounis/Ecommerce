<?php


use Gloudemans\Shoppingcart\Facades\Cart;

function presentPrice($price)
    {

        return number_format($price / 100, 2);


    }

    function setActiveCategory($category,$output='active'){

        return request()->category == $category ?$output:'';

    }

   function productImage($path){
       return $path && file_exists('storage/'.$path) ? asset('storage/'.$path) : asset('img/not-found.jpg');

   }

   function generalResponse($status , $message , $data , $status_code = 200) {
    $data = [
        'status' => $status ,
        'message' => $message ,
        'data' => $data ,
    ];
    return $data;
}

   function responseJson($status , $message , $data , $status_code = 200) {
    return response()->json(generalResponse($status , $message , $data , $status_code) , $status_code);
}



    function getNumbers(){

    $discount = session()->get('coupon')['discount']??0;
    $tax = config('cart.tax')/100;
    $newSubtotal = (Cart::subtotal()-$discount);
    if($newSubtotal < 0 ){
        $newSubtotal =0;
    }
    $code = session()->get('coupon')['name']??null;
    $newTax = $newSubtotal * $tax;
    $newTotal = $newSubtotal *(1+$tax);

    return collect(['discount'=>$discount,
        'newTax'=>$newTax,
        'newSubtotal'=>$newSubtotal,
        'newTotal'=>$newTotal,
        'tax'=>$tax,
        'code'=>$code]);

}










