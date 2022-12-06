<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

class ProductsController extends Controller
{
    public function getproducts (Request $request) {
        //create products requist
        $products = DB::table('dummy');
        
        //build requist
        if($request->search != '') {
            $search = $request->search;
            $products = $products->where(function ($query) use($search) {
                $query->where('product', 'like', '%' . $search . '%')
                      ->orWhere('category', 'like', '%' . $search . '%')
                      ->orWhere('brand', 'like', '%' . $search . '%');
                }
            );
        }

        // search for brands
        if(is_array($request->brands)) {
            $products = $products->whereIn('brand', $request->brands);
        }

        // search for categories
        if(is_array($request->categories)) {
            $products = $products->whereIn('category', $request->categories);
        }

        // send requist
        $products = $products->paginate(10);

        // return response
        return [
            "status" => "success",
            "items"  => $products
        ];
    }
}
