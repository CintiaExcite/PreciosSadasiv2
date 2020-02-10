<?php

namespace App\Http\Controllers\Api\Product;

use JWTAuth;
use App\User;
use App\Models\Log;
use App\Models\Product;
use App\Models\Payment;
use App\Models\HistoryPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductPaymentController extends ApiController
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        if (!User::searchPermitOnArray(["MP", "SMP"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $payment = $product->payment()->first();
        return $this->showOne($payment);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        if (!User::searchPermitOnArray(["MP", "SMP"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $rules = [
            'payments_from' => 'required',
        ];
        $messages = [
            'payments_from.required' => 'Pagos es requerido',
        ];
        $this->validate($request, $rules, $messages);
        $this->hasPayment($product);
        $payment = new Payment;
        $payment->product_id = $product->id;
        $payment->payments_from = $request->payments_from;
        $payment->show = 0;
        if ($payment->save()) { return $this->showOne($payment); }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, Payment $payment)
    {
        if (!User::searchPermitOnArray(["MP", "SMP"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        
        $this->verifyProduct($product, $payment);
        $payment->fill($request->only([ 'payments_from' ]));
        if ($request->has('payments_from')) { $payment->payments_from = $request->payments_from; }
        if ($request->has('show')) { $payment->show = $request->show; }
        if ($payment->isClean()) { return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422); }
        $change_payment = false;
        $change_show = false;
        if ($payment->isDirty('payments_from')) { $change_payment = true; }
        if ($payment->isDirty('show')) { $change_show = true; }
        if ($payment->save()) {
            $user = JWTAuth::parseToken()->authenticate();
            if ($change_payment) {
                $history_payment = new HistoryPayment;
                $history_payment->payment_id = $payment->id;
                $history_payment->payment = $payment->payments_from;
                $history_payment->save();
                Log::logProduct($user->id, 8, $product->id, $product->product);
            }
            if ($change_show) {
                Log::logProduct($user->id, 12, $product->id, $product->product);
            }
            return $this->showOne($payment); 
        }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }

    protected function hasPayment(Product $product)
    {
        $payment = $product->payment()->get();
        if ($payment != null) {
            return $this->errorResponse("El producto ya tiene un pago asignado", 422);
        }
    }

    protected function verifyProduct(Product $product, Payment $payment)
    {
        if ($product->id != $payment->product_id) {
            //throw new HttpException(422, 'El vendedor especificado no es el vendedor real del producto');
            return $this->errorResponse("El pago no pertenece al producto", 422);
        }
    }
}
