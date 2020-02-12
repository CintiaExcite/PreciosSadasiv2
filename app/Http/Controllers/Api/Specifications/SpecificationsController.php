<?php

namespace App\Http\Controllers\Api\Specifications;

use App\Models\Specification;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SpecificationsController extends ApiController
{
    public function index()
    {
        return Specification::orderBy('id','DESC')->get();
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
        $specification = new Specification;
        $specification->description = $request->description;
        $specification->save();

        return response()->json(['mensaje'=>'Especificación registrada','data'=>$specification]);
    }

    public function show(Specification $specification)
    {
        return $this->showOne($specification);
    }

    public function update(Request $request, Specification $specification)
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

    public function destroy(Specification $specification)
    {
        $specification->delete();
        return $this->showOne($specification);
    }
    
}


