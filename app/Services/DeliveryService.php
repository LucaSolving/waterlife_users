<?php

namespace App\Services;

class DeliveryService extends ApiService
{
    public function __construct(){
        //$this->endpoint = 'http://localhost:8002';
        $this->endpoint = 'http://26.220.92.239:8002';
        //$this->endpoint = 'http://localhost:8002/deliveries';
        //$this->endpoint = env('PRODUCTS_SERVICE_BASE_URL');
    }
}