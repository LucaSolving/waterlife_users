<?php

namespace App\Services;

//use App\Services\ProductService;

abstract class ApiService
{
    protected string $endpoint;

    public function get($path){
        //return $this->performRequest('GET', '/products');
        //return Http::get("{$this->endpoint}/{$path}",$data)->json();
        return \Http::get("$this->endpoint.$path")->json();
    }

    public function post($path, $data){
        return \Http::post("$this->endpoint.$path", $data)->json();
    }

    public function delete($path){
        return \Http::delete("$this->endpoint.$path")->json();
    }
    
}