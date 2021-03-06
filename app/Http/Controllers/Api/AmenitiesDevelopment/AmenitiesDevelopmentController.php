<?php

namespace App\Http\Controllers\Api\AmenitiesDevelopment;

use App\Models\AmenitiesDevelopment;
use App\Models\Amenity;
use App\Models\Development;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class AmenitiesDevelopmentController extends ApiController
{
    public function index(Development $Development)
    {
    	//return $Development;
    	$development = $Development;
    	foreach ($development->amenities as $amenity){
    		$amenity->description;
    		$amenity->pivot->development_id;
    	}

    	return $development;
	}

	public function store(Request $request)
    {

        $development= Development::find($request->development_id);
 
        $development->amenities()->attach($request->amenity_id,['development_id'=>$request->development_id]);  
          
        return response()->json(['mensaje'=>'registro exitoso','data'=>$development]);
	} 

	public function update(Request $request)
    {

        $development= Development::find($request->development_id);
 
         $development->amenities()->syncWithoutDetaching($request->amenity_id,['development_id'=>$request->development_id]); 
     
        return $this->showAll($development->amenities);
    }


}
