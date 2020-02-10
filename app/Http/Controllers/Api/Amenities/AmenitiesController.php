<?php

namespace App\Http\Controllers\Api\Amenities;

use App\Models\Amenities;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class AmenitiesController extends ApiController
{
    public function index()
    {
        return Amenities::orderBy('id','DESC')->get();
    }

    public function store(Request $request)
    {
        $rules = [
            'description' => 'required',
        ];
        $messages = [
            'description.required' => 'La descripción de la amenidad es requerida',
        ];

        $this->validate($request, $rules, $messages);
        $amenities = new Amenities;
        $amenities->description = $request->description;
        $amenities->save();

        return response()->json(['mensaje'=>'Amenidad registrada', 'data'=>$amenities]);
    }

    public function show(Amenities $amenity)
    {
        return $this->showOne($amenity);
    }

    public function update(Request $request, Amenities $amenity)
    {
        $rules = [
            'description' => 'required',
        ];
        $messages = [
            'description.required' => 'La descripción de la amenidad es requerida',
        ];

        $this->validate($request, $rules, $messages);
        if ($request->has('description')) {$amenity->description = $request->description;}
        if ($amenity->isClean()){
            return "error";
        }
        $amenity->save();
        return response()->json(['mensaje'=>'Data modificada','data'=>$amenity]);
    }

    public function destroy(Amenities $amenity)
    {
        $amenity->delete();
        return $this->showOne($amenity);
    }

}
