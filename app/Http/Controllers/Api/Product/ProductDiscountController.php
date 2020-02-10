<?php

namespace App\Http\Controllers\Api\Product;

use JWTAuth;
use App\User;
use App\Models\Log;
use App\Models\Product;
use App\Models\Discount;
use App\Models\HistoryDiscount;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductDiscountController extends ApiController
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        if (!User::searchPermitOnArray(["MP", "SMP"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $discount = $product->discount()->first();
        return $this->showOne($discount);
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
            'discount' => 'required',
        ];
        $messages = [
            'discount.required' => 'Descuento es requerido',
        ];
        $this->validate($request, $rules, $messages);
        $this->hasDiscount($product);
        $discount = new Discount;
        $discount->product_id = $product->id;
        $discount->discount = $request->discount;
        $payment->show = 0;
        if ($discount->save()) { return $this->showOne($discount); }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, Discount $discount)
    {
        if (!User::searchPermitOnArray(["MP", "SMP"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        
        $this->verifyProduct($product, $discount);
        $discount->fill($request->only([ 'discount', 'show' ]));
        if ($request->has('discount')) { $discount->discount = $request->discount; }
        if ($request->has('show')) { $discount->show = $request->show; }
        if ($discount->isClean()) { return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422); }
        $change_discount = false;
        $change_show = false;
        if ($discount->isDirty('discount')) { $change_discount = true; }
        if ($discount->isDirty('show')) { $change_show = true; }
        if ($discount->save()) {
            $user = JWTAuth::parseToken()->authenticate();
            if ($change_discount) {
                $history_discount = new HistoryDiscount;
                $history_discount->discount_id = $discount->id;
                $history_discount->discount = $discount->discount;
                $history_discount->save();
                Log::logProduct($user->id, 5, $product->id, $product->product);
            }
            if ($change_show) {
                Log::logProduct($user->id, 10, $product->id, $product->product);
            }
            return $this->showOne($discount); 
        }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }

    protected function hasDiscount(Product $product)
    {
        $discount = $product->discount()->get();
        if ($discount != null) {
            return $this->errorResponse("El producto ya tiene un descuento asignado", 422);
        }
    }

    protected function verifyProduct(Product $product, Discount $discount)
    {
        if ($product->id != $discount->product_id) {
            //throw new HttpException(422, 'El vendedor especificado no es el vendedor real del producto');
            return $this->errorResponse("El descuento no pertenece al producto", 422);
        }
    }
}
