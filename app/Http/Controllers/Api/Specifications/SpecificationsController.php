<?php

namespace App\Http\Controllers\Api\Specifications;

use App\Models\Specifications;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SpecificationsController extends ApiController
{
    public function index()
    {
        return Specifications::orderBy('id','DESC')->get();
    }

    public function store(Request $request)
    {
        $rules = [
            'description' => 'required',
        ];

        $messages = [
            'description.required' => 'La especificación es requerida',
        ];

        $this->validate($request, $rules, $messages);
        $specification = new Specifications;
        $specification->description = $request->description;
        $specification->save();

        return response()->json(['mensaje'=>'Especificación registrada','data'=>$specification]);
    }

    public function show(Specifications $specification)
    {
        return $this->showOne($specification);
    }

    public function update(Request $request, Specifications $specification)
    {
        $rules = [
            'description' => 'required',
        ];

        $messages = [
            'description.required' => 'La especificación es requerida',
        ];
        $this->validate($request, $rules, $messages);
        if ($request->has('description')) {$specification->description = $request->description;}
        if ($specification->isClean()){
            return "error";
        }
        $specification->save();
        return response()->json(['mensaje'=>'Especificación modificada', 'data'=>$specification]);
    }

    public function destroy(Specifications $specification)
    {
        $specification->delete();
        return $this->showOne($specification);
    }
    
}


