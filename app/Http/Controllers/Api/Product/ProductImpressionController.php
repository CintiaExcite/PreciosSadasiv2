<?php

namespace App\Http\Controllers\Api\Product;

use Illuminate\Http\Request;
use App\User;
use App\Models\Product;
use App\Http\Controllers\ApiController;

class ProductImpressionController extends ApiController
{
    public function index(Request $request, Product $product)
    {
    	if (!User::searchPermitOnArray(["MP", "SMP"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
    	$month = date('m');
    	$year = date('Y');
        if ($request->has('month')) { $month = $request->month; }
        if ($request->has('year')) { $year = $request->year; }
        $impression = $product->impressions()->whereMonth('date', $month)->whereYear('date', $year)->get();
        return $this->showAll($impression);
    }
}
