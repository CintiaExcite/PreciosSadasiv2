<?php

namespace App\Http\Controllers\Api\Development;

use App\Models\DevelopmentInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;


class DevelopmentInfoController extends ApiController
{
    public function index()
    {
        return DevelopmentInfo::orderBy('id','DESC')->get();
    }

    public function store(Request $request)
    {
        $rules = [
            'development_id' => 'required|integer',
            'location' => 'required',
            'phone' => 'required|numeric',
            'email' => 'required|email',
        ];
        $messages = [
            'development_id.required' => 'Desarrollo es requerido',
            'development_id.integer' => 'Desarrollo debe ser numérico',
            'location.required' => 'Ubicación es requerido',
            'phone.required' => 'Teléfono es requerido',
            'phone.numeric' => 'Teléfono es numérico',
            'email.required' => 'El campo Correo Electrónico es obligatorio.',
            'email.email' => 'Correo Electrónico no es un correo válido.',
        ];

        $this->validate($request, $rules, $messages);
        $developmentinfo = new DevelopmentInfo;
        $developmentinfo->development_id = $request->development_id;
        $developmentinfo->location = $request->location;
        $developmentinfo->phone = $request->phone;
        $developmentinfo->email = $request->email;
        $developmentinfo->save();

        return response()->json(['mensaje'=>'Info del desarrollo', 'data'=>$developmentinfo]);
    }

    public function show(DevelopmentInfo $development_info)
    {
        return $this->showOne($development_info);
    }

    public function update(Request $request, DevelopmentInfo $development_info)
    {
        $rules = [
            'development_id' => 'integer',
            'phone' => 'numeric',
            'email' => 'email',
        ];
        $messages = [
            'development_id.integer' => 'Desarrollo debe ser numérico',
            'phone.numeric' => 'Teléfono es numérico',
            'email.email' => 'Correo Electrónico no es un correo válido.',
        ];
        $this->validate($request, $rules, $messages);
        if ($request->has('development_id')) {$development_info->development_id = $request->development_id;}
        if ($request->has('location')) {$development_info->location = $request->location;}
        if ($request->has('phone')) {$development_info->phone = $request->phone;}
        if ($request->has('email')) {$development_info->email = $request->email;}
        if ($development_info->isClean()){
            return "error";
        }
        $development_info->save();
        return response()->json(['mensaje'=>'Data modificada','data'=>$development_info]);
    }

    public function destroy(DevelopmentInfo $development_info)
    {
        $development_info->delete();
        return $this->showOne($development_info);
    }
}
