<?php

namespace App\Http\Controllers;

use App\Services\DeliveryService;
use Illuminate\Http\Request;

class DeliveriesController extends Controller
{
    public $deliveryService;

    public function __construct(DeliveryService $deliveryService)
    {
        $this->deliveryService = $deliveryService;
    }

    public function show(){
        return view('home.deliveries');
    }

}
