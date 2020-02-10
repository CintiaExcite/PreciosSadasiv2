<?php

namespace App\Http\Controllers\Api\Development;

use App\User;
use App\Models\Log;
use App\Models\Development;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class DevelopmentProductController extends ApiController
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Development $development)
    {
        /*if (!User::searchPermitOnArray(["D", $development->state_id]) && !User::searchPermitEOnArray($development->id)) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        
        if (User::searchPermitOnArray("D")) {*/
            $products = $development->products()
                ->with('price', 'discount', 'income', 'payment')
                ->where('products.status', 1)
                ->get();
        /*} else {
            $products = $development->products()
                ->with('price', 'discount', 'income', 'payment')
                ->where('products.status', 1)
                ->get();
        }*/
        
        return $this->showAll($products);
    }
}
