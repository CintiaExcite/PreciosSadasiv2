<?php

namespace App\Http\Controllers\Api\Discount;

use App\User;
use App\Models\Log;
use App\Models\Discount;
use App\Models\HistoryDiscount;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class DiscountHistoryDiscountController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Discount $discount)
    {
        if (!User::searchPermitOnArray(["M", "SCO"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $history_discount = $discount->history_discounts()->get();
        for ($i=0; $i < count($history_discount); $i++) { 
            if ($i == 0) {
                $history_discount[$i]['up_down_discount'] = 0;
            } else {
                $history_discount[$i]['discount'] > $history_discount[$i - 1]['discount'] ? $history_discount[$i]['up_down_discount'] = 0 : $history_discount[$i]['up_down_discount'] = 1;
            }
        }
        return $this->showAll($history_discount);
    }
}
