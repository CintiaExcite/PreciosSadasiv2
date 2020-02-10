<?php

namespace App\Http\Controllers\Api\Price;

use App\User;
use App\Models\Log;
use App\Models\Price;
use App\Models\HistoryPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class PriceHistoryPriceController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Price $price)
    {
        if (!User::searchPermitOnArray(["M", "SCO"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $history_price = $price->history_prices()->get();
        for ($i=0; $i < count($history_price); $i++) { 
            if ($i == 0) {
                $history_price[$i]['up_down_price'] = 0;
            } else {
                $history_price[$i]['price'] > $history_price[$i - 1]['price'] ? $history_price[$i]['up_down_price'] = 0 : $history_price[$i]['up_down_price'] = 1;
            }
        }
        return $this->showAll($history_price);
    }
}
