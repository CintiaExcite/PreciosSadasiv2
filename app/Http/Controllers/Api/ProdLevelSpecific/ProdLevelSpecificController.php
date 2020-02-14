<?php

namespace App\Http\Controllers\Api\ProdLevelSpecific;

use App\Models\Product;
use App\Models\Level;
//use App\Models\Specifications;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProdLevelSpecificController extends ApiController
{
    public function index(Product $Product)
    {
        $products = $Product;
        foreach ($products->levels as $level){
            //traigo los datos del nivel
            $level->level;
            //traigo los datos de la tabla pivote
            $level->pivot->specification_id;
            $level->pivot->quantity;

        }

        foreach ($products->specification as $specification){
            //traigo los datos de la especificación
            $specification->description;
            //traigo los datos de la tabla pivote
            $specification->pivot->specification_id;
            $specification->pivot->quantity;
        }

        return $products;
    }

    public function store(Request $request)
    {

        $product= Product::findOrFail($request->product_id);
        $product->levels()->attach($request->level_id,['specification_id'=>$request->specification_id, 'quantity'=>$request->quantity]);

    }

    public function update(Request $request, Product $product)
    {
        $product->levels()->syncWithoutDetaching($request->level_id,['specification_id'=>$request->specification_id,'quantity'=>$request->quantity]);
    }


}



/*$user= Product::find(3);

$user->tasks()->attach('Aquí id task',['menu_id'=>'id menu', 'status'=>true]);*/