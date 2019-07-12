<?php

namespace App\Http\Controllers;

use App\Products as Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    //TODO Abstract out setProductsValues so it can be shared between
    //ProductsController and CreativesController


    /*
        EXAMPLE INPUT
        {
            "products":[
                {
                    "creatives_id":2,
                    "product_type":1
                    "name":"FooArt Small T-Shirt",
                },
                {
                    "creatives_id":2,
                    "product_type":1,
                    "name":"FooArt Print"
                }
            ]
        }
    */
    public function createProducts( Request $request ){
        $inputs = $request->input('products');
        $created = [];
        foreach($inputs as $input){
            $creative = new Products;
            $created[] = $this->setProductsValues($creative, $input);
        }

        return json_encode($created);
    }

    //dynamically check if input is a property of Products and set it
    public function setProductsValues(Products $product, array $input){
        $allowedKeys = ['creatives_id', 'name', 'product_type'];
        foreach($input as $key => $value){
            if(in_array($key, $allowedKeys)){
                $product->{$key} = $value;
            }
        }
        $product->save();
        return $product->id;
    }
}
