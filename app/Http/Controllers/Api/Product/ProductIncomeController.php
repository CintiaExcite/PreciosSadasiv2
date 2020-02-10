<?php

namespace App\Http\Controllers\Api\Product;

use JWTAuth;
use App\User;
use App\Models\Log;
use App\Models\Product;
use App\Models\Income;
use App\Models\HistoryIncome;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductIncomeController extends ApiController
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        if (!User::searchPermitOnArray(["MP", "SMP"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $income = $product->income()->first();
        return $this->showOne($income);
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
            'income_from' => 'required',
        ];
        $messages = [
            'income_from.required' => 'Ingresos es requerido',
        ];
        $this->validate($request, $rules, $messages);
        $this->hasIncome($product);
        $income = new Income;
        $income->product_id = $product->id;
        $income->income_from = $request->income_from;
        $payment->show = 0;
        if ($income->save()) { return $this->showOne($income); }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, Income $income)
    {
        if (!User::searchPermitOnArray(["MP", "SMP"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        
        $this->verifyProduct($product, $income);
        $income->fill($request->only([ 'income_from', 'show' ]));
        if ($request->has('income_from')) { $income->income_from = $request->income_from; }
        if ($request->has('show')) { $income->show = $request->show; }
        if ($income->isClean()) { return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422); }
        $change_income = false;
        $change_show = false;
        if ($income->isDirty('income_from')) { $change_income = true; }
        if ($income->isDirty('show')) { $change_show = true; }
        if ($income->save()) {
            $user = JWTAuth::parseToken()->authenticate();
            if ($change_income) {
                $history_income = new HistoryIncome;
                $history_income->income_id = $income->id;
                $history_income->income = $income->income_from;
                $history_income->save();
                Log::logProduct($user->id, 7, $product->id, $product->product);
            }
            if ($change_show) {
                Log::logProduct($user->id, 11, $product->id, $product->product);
            }
            return $this->showOne($income); 
        }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }

    protected function hasIncome(Product $product)
    {
        $income = $product->income()->get();
        if ($income != null) {
            return $this->errorResponse("El producto ya tiene un ingreso asignado", 422);
        }
    }

    protected function verifyProduct(Product $product, Income $income)
    {
        if ($product->id != $income->product_id) {
            //throw new HttpException(422, 'El vendedor especificado no es el vendedor real del producto');
            return $this->errorResponse("El ingreso no pertenece al producto", 422);
        }
    }
}
