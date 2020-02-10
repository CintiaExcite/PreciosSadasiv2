<?php

namespace App\Http\Controllers\Api\Level;

use App\Models\Level;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class LevelController extends ApiController
{
    public function index()
    {
        return Level::orderBy('id','DESC')->get();
    }

    public function store(Request $request)
    {
        $rules = [
            'level' => 'required',
        ];
        $messages = [
            'level.required' => 'El nivel es requerido',
        ];

        $this->validate($request, $rules, $messages);
        $level = new Level;
        $level->level = $request->level;
        $level->save();

        return response()->json(['mensaje'=>'Nivel registrado','data'=>$level]);
    }

    public function show(Level $level)
    {
        return $this->showOne($level);
    }

    public function update(Request $request, Level $level)
    {
        $rules = [
            'level' => 'required',
        ];
        $messages = [
            'level.required' => 'El nivel es requeridoooooo',
        ];

        $this->validate($request, $rules, $messages);
        if ($request->has('level')) {$level->level = $request->level;}
        if ($level->isClean()){
            return "error";
        }
        $level->save();
        return response()->json(['mensaje'=>'Nivel modificado', 'data'=>$level]);
    }

    public function destroy(Level $level)
    {
        $level->delete();
        return $this->showOne($level);
    }
}
