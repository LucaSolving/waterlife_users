<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
        //$this->middleware('auth:client.credentials');
    }

    public function show(){
        return view('home.products');
    }

    public function productsservices(){
        $products1 = $this->productService->get('/products');
        return $products1;
    }

    


    

}
