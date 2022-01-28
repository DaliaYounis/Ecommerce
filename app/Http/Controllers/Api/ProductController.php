<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

//models
use App\Models\Product;

//request
use Illuminate\Http\Request;

// support
use Illuminate\Support\Facades\Hash;
use DB;

//trait
use Illuminate\Foundation\Auth\ThrottlesLogins;


class ProductController extends Controller
{
   public function index(){

       $product = Product::find(1);
       return responseJson(true ,$product, [] , 422);

   }



}
