<?php

namespace App\Http\Controllers\Api\Payment;

use App\User;
use App\Models\Log;
use App\Models\Payment;
use App\Models\HistoryPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class PaymentHistoryPaymentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Payment $payment)
    {
        if (!User::searchPermitOnArray(["M", "SCO"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $history_payment = $payment->history_payments()->get();
        for ($i=0; $i < count($history_payment); $i++) { 
            if ($i == 0) {
                $history_payment[$i]['up_down_payment'] = 0;
            } else {
                $history_payment[$i]['payment'] > $history_payment[$i - 1]['payment'] ? $history_payment[$i]['up_down_payment'] = 0 : $history_payment[$i]['up_down_payment'] = 1;
            }
        }
        return $this->showAll($history_payment);
    }
}
