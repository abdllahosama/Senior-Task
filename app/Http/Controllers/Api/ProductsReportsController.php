<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

class ProductsReportsController extends Controller
{
    public function getproductsanalytics (Request $request) {
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

        
        // get categories data
        $categories = array();
        $categories[0] = $products->clone()->where('category', 'category-1')->count();
        $categories[1] = $products->clone()->where('category', 'category-2')->count();
        $categories[2] = $products->clone()->where('category', 'category-3')->count();

        // get categories brands
        $brands = array();
        $brands[0] = $products->clone()->where('brand', 'brand-1')->count();
        $brands[1] = $products->clone()->where('brand', 'brand-2')->count();
        $brands[2] = $products->clone()->where('brand', 'brand-3')->count();

        // return response
        return [
            "status" => "success",
            "data"  => [
                "categories" => $categories,
                "brands"    => $brands 
            ]
        ];
    }
}
