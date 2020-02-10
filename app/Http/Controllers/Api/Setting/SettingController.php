<?php

namespace App\Http\Controllers\Api\Setting;

use App\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SettingController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexEmail()
    {
        if (!User::searchPermitOnArray("OPC")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
       	$settings = Setting::where('type', 'email')->orderBy('extra_value', 'asc')->get();
        return $this->showAll($settings);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $state
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        if (!User::searchPermitOnArray("OPC")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        return $this->showOne($setting);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $state
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        if (!User::searchPermitOnArray("OPC")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $rules = [
            'value' => 'required',
        ];
        $messages = [
            'value.required' => 'Valor es requerido',
        ];
        $this->validate($request, $rules, $messages);
        if ($request->has('value')) { $setting->value = $request->value; }
        if ($setting->isClean()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }
        if ($setting->save()) {
            return $this->showOne($setting);
        }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }
}
