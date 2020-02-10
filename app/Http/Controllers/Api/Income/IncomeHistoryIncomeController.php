<?php

namespace App\Http\Controllers\Api\Income;

use App\User;
use App\Models\Log;
use App\Models\Income;
use App\Models\HistoryIncome;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class IncomeHistoryIncomeController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Income $income)
    {
        if (!User::searchPermitOnArray(["M", "SCO"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $history_income = $income->history_incomes()->get();
        for ($i=0; $i < count($history_income); $i++) { 
            if ($i == 0) {
                $history_income[$i]['up_down_income'] = 0;
            } else {
                $history_income[$i]['income'] > $history_income[$i - 1]['income'] ? $history_income[$i]['up_down_income'] = 0 : $history_income[$i]['up_down_income'] = 1;
            }
        }
        return $this->showAll($history_income);
    }
}
