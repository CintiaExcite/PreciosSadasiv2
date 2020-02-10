<?php

namespace App\Http\Controllers\Api\Development;

use JWTAuth;
use App\User;
use App\UserPermit;
use App\Models\Log;
use App\Models\Product;
use App\Models\SendEmail;
use App\Models\Development;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ApiController;

class DevelopmentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*if (!User::searchPermitOnArray(["D", "SCO"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        if (User::searchPermitOnArray("D")) {*/
            $developments = Development::with(['state'])->where('status', 1)->get();
        /*} else {
            $permits_e = User::permitsEArray();
            $developments = Development::with(['state'])->where('status', 1)->whereIn('id', $permits_e)->get();
        }*/
        return $this->showAll($developments);
    }

    /**
     * Display a listing of the resource for datatable.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexDT(Request $request)
    {
        if (!User::searchPermitOnArray(["D", "SCO"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $columns = ['id', 'state_id', 'development', 'status'];
        $page = $request->input('page');
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $searchValue = $request->input('search');
        if (User::searchPermitOnArray("D")) {
            $query = Development::select('id', 'state_id', 'development', 'status')->with(['state'])->orderBy($columns[$column], $dir);
        } else {
            $permits_e = User::permitsEArray();
            $query = Development::select('id', 'state_id', 'development', 'status')->with(['state'])->whereIn('id', $permits_e)->orderBy($columns[$column], $dir);
        }
        if ($searchValue) {
            $query->where(function($query) use ($searchValue) {
                $query->where('state', 'like', '%' . $searchValue . '%');
            });
        }
        $developments = $query->paginate($length, ['*'], 'page', $page);
        $custom = collect(['draw' => $request->input('draw')]);
        $data = $custom->merge($developments);
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
        if (!User::searchPermitOnArray("DC")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $rules = [
            'state_id' => 'required|integer',
            'development' => 'required',
            'status' => 'required|integer',
        ];
        $messages = [
            'state_id.required' => 'Estado es requerido',
            'state_id.integer' => 'Estado debe ser numérico',
            'development.required' => 'Desarrollo es requerido',
            'status.required' => 'Estatus es requerido',
            'status.integer' => 'Estatus debe ser numérico',
        ];
        $this->validate($request, $rules, $messages);
        $development = new Development;
        $development->state_id = $request->state_id;
        $development->development = $request->development;
        $development->status = $request->status;
        if ($development->save()) {
            $user_permit = new UserPermit;
            $user_permit->description = $development->development;
            $user_permit->identifier = $development->id;
            $user_permit->company = "Sadasi";
            $user_permit->state = $development->state_id;
            $user_permit->status = 1;
            $user_permit->save();
            $user = JWTAuth::parseToken()->authenticate();
            Log::logDevelopment($user->id, 1, $development->id, $development->development);
            SendEmail::EmailCreateDevelopment($development);
            return $this->showOne($development);
        }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Development  $development
     * @return \Illuminate\Http\Response
     */
    public function show(Development $development)
    {
        /*if (!User::searchPermitOnArray(["D", "SCO"])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);*/
        return $this->showOne($development);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Development  $development
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Development $development)
    {
        if (!User::searchPermitOnArray("DE")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $rules = [
            'state_id' => 'integer',
            'status' => 'integer',
        ];
        $messages = [
            'state_id.integer' => 'Estado debe ser numérico',
            'status.integer' => 'Estatus debe ser numérico',
        ];
        $this->validate($request, $rules, $messages);
        if ($request->has('state_id')) { $development->state_id = $request->state_id; }
        if ($request->has('development')) { $development->development = $request->development; }
        if ($request->has('status')) { $development->status = $request->status; }
        if ($development->isClean()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }
        if ($development->save()) {
            $user_permit = UserPermit::where([['identifier', $development->id],['state', '!=', 0]])->first();
            $user_permit->description = $development->development;
            $user_permit->state = $development->state_id;
            $user_permit->save();
            $user = JWTAuth::parseToken()->authenticate();
            Log::logDevelopment($user->id, 2, $development->id, $development->development);
            SendEmail::EmailEditDevelopment($development);
            return $this->showOne($development);
        }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Development  $development
     * @return \Illuminate\Http\Response
     */
    public function destroy(Development $development)
    {
        if (!User::searchPermitOnArray("DD")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        if ($development->delete()) {
            $user_permit = UserPermit::where([['identifier', $development->id],['state', '!=', 0]])->first();
            $user_permit->status = 0;
            $user_permit->save();
            SendEmail::EmailDeleteDevelopment($development);
            $this->deleteProducts($development);
            return $this->showOne($development);
        }
        return $this->errorResponse("No se ha podido borrar el registro.", 422);
    }

    public function deleteProducts($development) {
        $products = Product::where('development_id', $development->id)->get();
        foreach ($products as $key => $product) { $product->delete(); }
    }

    public function updateDevelopmentImage(Request $request, Development $development)
    {
        //dd($request);
        if (!User::searchPermitOnArray("DE")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $rules = [ 'image_sys' => 'file', ];
        $messages = [ 'image_sys.file' => 'Imagen debe ser un archivo', ];
        $this->validate($request, $rules, $messages);
        if ($request->has('image_sys')) {
            $development_path = str_replace("http://sadasi.test/precios/public/files/", "", $development->image_sys);
            Storage::disk('files')->delete($development_path);
            $development->image_sys = $request->image_sys->store('developments/logos');
        }
        if ($development->save()) {
            $user = JWTAuth::parseToken()->authenticate();
            Log::logDevelopment($user->id, 3, $development->id, $development->development);
            return $this->showOne($development);
        }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }
}