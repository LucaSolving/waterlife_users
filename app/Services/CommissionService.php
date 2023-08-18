<?php

namespace App\Services;

class CommissionService extends ApiService
{
    public function __construct(){
        //$this->endpoint = 'http://localhost:8006';
        $this->endpoint = 'http://26.220.92.239:8006';
    }
}