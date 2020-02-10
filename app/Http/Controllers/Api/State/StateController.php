<?php

namespace App\Http\Controllers\Api\State;

use JWTAuth;
use App\User;
use App\UserPermit;
use App\Models\Log;
use App\Models\State;
use App\Models\Product;
use App\Models\Development;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class StateController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!User::searchPermitOnArray(["E", "SCO"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        if (User::searchPermitOnArray("E")) {
            $states = State::where('status', 1)->get();
        } else {
            $permits = User::permitsArray();
            $states = State::where('status', 1)->whereIn('id', $permits)->get();
        }
        return $this->showAll($states);
    }

    /**
     * Display a listing of the resource for datatable.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexDT(Request $request)
    {
        if (!User::searchPermitOnArray(["E", "SCO"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $columns = ['id', 'state', 'status'];
        $page = $request->input('page');
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $searchValue = $request->input('search');
        if (User::searchPermitOnArray("E")) {
            $query = State::select('id', 'state', 'status')->orderBy($columns[$column], $dir);
        } else {
            $permits = User::permitsArray();
            $query = State::select('id', 'state', 'status')->whereIn('id', $permits)->orderBy($columns[$column], $dir);
        }
        if ($searchValue) {
            $query->where(function($query) use ($searchValue) {
                $query->where('state', 'like', '%' . $searchValue . '%');
            });
        }
        $states = $query->paginate($length, ['*'], 'page', $page);
        $custom = collect(['draw' => $request->input('draw')]);
        $data = $custom->merge($states);
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!User::searchPermitOnArray("EC")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $rules = [
            'state' => 'required',
            'status' => 'required|integer',
        ];
        $messages = [
            'state.required' => 'Nombre del estado es requerido',
            'status.required' => 'Estatus es requerido',
            'status.integer' => 'Estatus debe ser numérico',
        ];
        $this->validate($request, $rules, $messages);
        $state = new State;
        $state->state = $request->state;
        $state->status = $request->status;
        if ($state->save()) {
            $user_permit = new UserPermit;
            $user_permit->description = "Sadasi " . $state->state;
            $user_permit->identifier = $state->id;
            $user_permit->company = "Sadasi";
            $user_permit->state = 0;
            $user_permit->status = 1;
            $user_permit->save();
            $user = JWTAuth::parseToken()->authenticate();
            Log::logState($user->id, 1, $state->id, $state->state);
            return $this->showOne($state);
        }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function show(State $state)
    {
        if (!User::searchPermitOnArray(["E", "SCO"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        return $this->showOne($state);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, State $state)
    {
        if (!User::searchPermitOnArray("EE")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $rules = [
            'status' => 'integer',
        ];
        $messages = [
            'status.integer' => 'Estatus debe ser numérico',
        ];
        $this->validate($request, $rules, $messages);
        if ($request->has('state')) { $state->state = $request->state; }
        if ($request->has('status')) { $state->status = $request->status; }
        if ($state->isClean()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }
        if ($state->save()) {
            $user_permit = UserPermit::where([['identifier', $state->id],['state', 0]])->first();
            $user_permit->description = "Sadasi " . $state->state;
            $user_permit->save();
            $user = JWTAuth::parseToken()->authenticate();
            Log::logState($user->id, 2, $state->id, $state->state);
            return $this->showOne($state);
        }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function destroy(State $state)
    {
        if (!User::searchPermitOnArray("ED")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        if ($state->delete()) {
            $user_permit = UserPermit::where([['identifier', $state->id],['state', 0]])->first();
            $user_permit->status = 0;
            $user_permit->save();
            $this->deleteDevelopments($state);
            return $this->showOne($state); 
        }
        return $this->errorResponse("No se ha podido borrar el registro.", 422);
    }

    public function deleteDevelopments($state) {
        $developments = Development::where('state_id', $state->id)->get();
        foreach ($developments as $key => $development) {
            $this->deleteProducts($development);
            $development->delete();
        }
    }

    public function deleteProducts($development) {
        $products = Product::where('development_id', $development->id)->get();
        foreach ($products as $key => $product) { $product->delete(); }
    }
}