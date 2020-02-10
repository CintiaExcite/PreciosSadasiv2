<?php

namespace App\Http\Controllers\Api\Price;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class NumbersLettersController extends ApiController
{
    public function indexletras()
    {
    	//return NumeroALetras::convertir(99.99, 'soles');
    	//$numeroALetras = NumeroALetras::convert(99.99, 'soles');
		//return $numeroALetras;
    	/*$letras = \NumeroALetras::convertir(12345.62);
    	return $letras;*/

    	// Ejemplo 1

    	//return $this->showOne($price);

			$precio  = "1234532.62";
			$precio1 = explode(".", $precio);
			$precio2 = \NumeroALetras::convertir($precio1[0]);
			$precio3 = \NumeroALetras::convertir($precio1[1]);

			return response()->json(['DATA'=>$precio2.'PESOS CON '.$precio3.'CENTAVOS']);

			

    }
}
