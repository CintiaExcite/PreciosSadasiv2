<?php

namespace App\Http\Controllers\Api\Token;

use App\Models\Token;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class TokenController extends ApiController
{
    public function index()
    {
        return Token::orderBy('id','DESC')->get();
    }

    public function store(Request $request)
    {
        $rules = [
            'development_id' => 'required|integer',
            'cf_salesup' =>'required',
            'cf_salesup_tk_medio' => 'required',
            'cf_salesup_tk_cuenta' => 'required',
            'cf_salesup_tk_desarrollo' => 'required',
            'cf_salesup_id_estado' => 'required',
            'cf_salesup_tk_region' => 'required',
            'cf_salesup_tk_campania' => 'required',
        ];
        $messages = [
            'development_id.required' => 'Desarrollo es requerido',
            'development_id.integer' => 'Desarrollo debe ser numérico',
            'cf_salesup.required' => 'Token es requerido',
            'cf_salesup_tk_medio.required' => 'Token es requerido',
            'cf_salesup_tk_cuenta.required' => 'Token es requerido',
            'cf_salesup_tk_desarrollo.required' => 'Token es requerido',
            'cf_salesup_id_estado.required' => 'Token es requerido',
            'cf_salesup_tk_region.required' => 'Token es requerido',
            'cf_salesup_tk_campania.required' => 'Token es requerido',
        ];

        $this->validate($request, $rules, $messages);
        $token = new Token;
        $token->development_id = $request->development_id;
        $token->cf_salesup = $request->cf_salesup;
        $token->cf_salesup_tk_medio = $request->cf_salesup_tk_medio;
        $token->cf_salesup_tk_cuenta = $request->cf_salesup_tk_cuenta;
        $token->cf_salesup_tk_desarrollo = $request->cf_salesup_tk_desarrollo;
        $token->cf_salesup_id_estado = $request->cf_salesup_id_estado;
        $token->cf_salesup_tk_region = $request->cf_salesup_tk_region;
        $token->cf_salesup_tk_campania = $request->cf_salesup_tk_campania;
        $token->save();

        return response()->json(['mensaje'=>'Tokens registrados', 'data'=>$token]);

    }

    public function show(Token $token)
    {
        return $this->showOne($token);
    }

    public function update(Request $request, Token $token)
    {
        $rules = [
            'development_id' => 'integer',
        ];
        $messages = [
            'development_id.integer' => 'Desarrollo debe ser numérico',
        ];
        $this->validate($request, $rules, $messages);
        if ($request->has('development_id')) {$token->development_id = $request->development_id;}
        if ($request->has('cf_salesup')) {$token->cf_salesup = $request->cf_salesup;}
        if ($request->has('cf_salesup_tk_medio')) {$token->cf_salesup_tk_medio = $request->cf_salesup_tk_medio;}
        if ($request->has('cf_salesup_tk_cuenta')) {$token->cf_salesup_tk_cuenta = $request->cf_salesup_tk_cuenta;}
        if ($request->has('cf_salesup_tk_desarrollo')) {$token->cf_salesup_tk_desarrollo = $request->cf_salesup_tk_desarrollo;}
        if ($request->has('cf_salesup_id_estado')) {$token->cf_salesup_id_estado = $request->cf_salesup_id_estado;}
        if ($request->has('cf_salesup_tk_region')) {$token->cf_salesup_tk_region = $request->cf_salesup_tk_region;}
        if ($request->has('cf_salesup_tk_campania')) {$token->cf_salesup_tk_campania = $request->cf_salesup_tk_campania;}
        if ($token->isClean()){
            return "error";
        }
        $token->save();
        return response()->json(['mensaje'=>'Data modificada','data'=>$token]);
    }

    public function destroy(Token $token)
    {
        $token->delete();
        //return response()->json(['mensaje'=>'Token eliminado', 'data'=>$token]);
        return $this->showOne($token);
    }
        
}
