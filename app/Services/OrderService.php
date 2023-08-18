<?php

namespace App\Services;

class OrderService extends ApiService
{
    public function __construct(){
        //$this->endpoint = 'http://localhost:8004';
        $this->endpoint = 'http://26.220.92.239:8004';
        //$this->endpoint = env('PRODUCTS_SERVICE_BASE_URL');
    }
}