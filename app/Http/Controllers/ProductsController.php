<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Products;
use App\Category;
use App\CategorisedProducts;
use App\ProductsByCategory;

class ProductsController extends Controller
{
    public function GetAllProducts()
    {
        $products = Products::all();
        return $products;
    }

    public function GetConditionalProducts(Request $request)
    { 
        if($request->data['value'] == 1)
        {
            $products = Products::all();
            return $products;
        }
        else
        {
            $products = ProductsByCategory::where($request->data['field'], $request->data['operator'], $request->data['value'])->get();
            return $products;
        }
    }

    public function GetCategories()
    {
        $categories = Category::all();
        return $categories;
    }

    public function AddCategory(Request $request)
    {
        $category = new Category;
        $category->category_name = $request->data;
        $category->save();
    }

    public function AddProductToCategory(Request $request)
    {
        $categorised = new CategorisedProducts;
        $categorised->category_id = $request->data['category_id'];
        $categorised->product_id = $request->data['product_id'];
        $categorised->save();
    }
}
