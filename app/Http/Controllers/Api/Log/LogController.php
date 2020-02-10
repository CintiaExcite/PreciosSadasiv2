<?php

namespace App\Http\Controllers\Api\Log;

use App\User;
use App\Models\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
//https://docs.laravel-excel.com/3.1/exports/
class LogController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!User::searchPermitOnArray("L")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        if ($request->event == "estado") {
            $logs = Log::with('state', 'development', 'product', 'user')->where('event', $request->event)->where('state_id', $request->id)->get();
        }
        if ($request->event == "desarrollo") {
            $logs = Log::with('state', 'development', 'product', 'user')->where('event', $request->event)->where('development_id', $request->id)->get();
        }
        if ($request->event == "modelo") {
            $logs = Log::with('state', 'development', 'product', 'user')->where('event', $request->event)->where('product_id', $request->id)->get();
        }
        return $this->showAll($logs);
    }
}
