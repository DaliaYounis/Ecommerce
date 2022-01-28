<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory,Searchable;

    public function presentPrice(){

        return number_format($this->price/100,2);
    }

    public function scopeMightAlsoLike($query){
        return $query->inRandomOrder()->take(4);
    }
    public function categories(){
        return $this->belongsToMany('App\Models\Category');
    }

    public function orders(){

        return $this->belongsToMany(Order::class);
    }
}
