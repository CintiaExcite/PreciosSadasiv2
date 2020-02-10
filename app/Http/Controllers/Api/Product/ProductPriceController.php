<?php

namespace App\Http\Controllers\Api\Product;

use JWTAuth;
use App\User;
use App\Models\Log;
use App\Models\Product;
use App\Models\Price;
use App\Models\HistoryPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductPriceController extends ApiController
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        /*if (!User::searchPermitOnArray(["MP", "SMP"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);*/
        $price = $product->price()->first();
        return $this->showOne($price);
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
            'price' => 'required',
        ];
        $messages = [
            'price.required' => 'Precio es requerido',
        ];
        $this->validate($request, $rules, $messages);
        $this->hasPrice($product);
        $price = new Price;
        $price->product_id = $product->id;
        $price->price = $request->price;
        $price->text_before_price = $request->text_before_price;
        $price->text_after_price = $request->text_after_price;
        if ($price->save()) { return $this->showOne($price); }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, Price $price)
    {
        if (!User::searchPermitOnArray(["MP", "SMP"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        
        $this->verifyProduct($product, $price);
        $price->fill($request->only([ 'price', 'text_before_price', 'text_after_price' ]));
        if ($request->has('price')) { $price->price = $request->price; }
        if ($request->has('text_before_price')) { $price->text_before_price = $request->text_before_price; }
        if ($request->has('text_after_price')) { $price->text_after_price = $request->text_after_price; }
        if ($price->isClean()) { return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422); }
        $change_price = false;
        $change_texts_price = false;
        if ($price->isDirty('price')) { $change_price = true; }
        if ($price->isDirty('text_before_price') || $price->isDirty('text_after_price')) { $change_texts_price = true; }
        if ($price->save()) {
            $user = JWTAuth::parseToken()->authenticate();
            if ($change_price) {
                $history_price = new HistoryPrice;
                $history_price->price_id = $price->id;
                $history_price->price = $price->price;
                $history_price->save();
                Log::logProduct($user->id, 3, $product->id, $product->product);
            }
            if ($change_texts_price) {
                Log::logProduct($user->id, 9, $product->id, $product->product);   
            }
            return $this->showOne($price); 
        }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }

    protected function hasPrice(Product $product)
    {
        $price = $product->price()->get();
        if ($price != null) {
            return $this->errorResponse("El producto ya tiene un precio asignado", 422);
        }
    }

    protected function verifyProduct(Product $product, Price $price)
    {
        if ($product->id != $price->product_id) {
            //throw new HttpException(422, 'El vendedor especificado no es el vendedor real del producto');
            return $this->errorResponse("El precio no pertenece al producto", 422);
        }
    }
}