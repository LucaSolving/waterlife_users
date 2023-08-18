<?php

namespace App\Services;

class ProductService extends ApiService
{
    public function __construct(){
        //$this->endpoint = 'http://localhost:8001';
        $this->endpoint = 'http://26.220.92.239:8001';
        //$this->endpoint = 'http://localhost:8001/products';
        //$this->endpoint = env('PRODUCTS_SERVICE_BASE_URL');
    }
}