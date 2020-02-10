<?php

namespace App\Http\Controllers\Api\User;

use JWTAuth;
use App\User;
use App\Models\Log;
use App\Models\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!User::searchPermitOnArray("U")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $users = User::where([['status', 1], ['id', '!=', 1]])->get();
        return $this->showAll($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!User::searchPermitOnArray("UC")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ];
        $messages = [
            'name.required' => 'El campo Nombre es obligatorio.',
            'email.required' => 'El campo Correo Electrónico es obligatorio.',
            'email.email' => 'Correo Electrónico no es un correo válido.',
            'email.unique' => 'Correo Electrónico ya ha sido registrado.',
            'password.required' => 'El campo Contraseña es obligatorio.',
        ];
        $this->validate($request, $rules, $messages);
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->cellphone = $request->cellphone;
        $user->password = bcrypt($request->password);
        $user->status = 1;
        if ($user->save()) { 
            $user_auth = JWTAuth::parseToken()->authenticate();
            Log::logUser($user_auth->id, 1, $user->id, $user->name);
            return $this->showOne($user); 
        }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if (!User::searchPermitOnArray("U")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if (!User::searchPermitOnArray("UE")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $rules = [
            'name' => 'required',
            'email' => 'email|unique:users,email,' . $user->id,
        ];
        $messages = [
            'name.required' => 'El campo Nombre es obligatorio.',
            'email.email' => 'Correo Electrónico no es un correo válido.',
            'email.unique' => 'Correo Electrónico ya ha sido registrado.',
            'company.required' => 'El campo Companía es obligatorio.',
            'permits.required' => 'Permisos son obligatorios.',
        ];
        $this->validate($request, $rules, $messages);
        if ($request->has('name')) { $user->name = $request->name; }
        if ($request->has('email')) { $user->email = $request->email; }
        if ($request->has('cellphone')) { $user->cellphone = $request->cellphone; }
        if ($request->has('password')) { $user->password = bcrypt($request->password); }
        if ($request->has('status')) { $user->status = $request->status; }
        if ($user->isClean()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }
        if ($user->save()) { 
            $user_auth = JWTAuth::parseToken()->authenticate();
            Log::logUser($user_auth->id, 2, $user->id, $user->name);
            return $this->showOne($user); 
        }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (!User::searchPermitOnArray("UD")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        if ($user->delete()) { return $this->showOne($user); }
        return $this->errorResponse("No se ha podido borrar el registro.", 422);
    }

    /**
     * Update permits for the specified resource in storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updatePermits(Request $request, User $user)
    {
        if (!User::searchPermitOnArray("UE")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $rules = [
            'company' => 'required',
            'permits' => 'required'
        ];
        $messages = [
            'company.required' => 'El campo compañia es obligatorio.',
            'permits.required' => 'Permisos son obligatorios.',
        ];
        $this->validate($request, $rules, $messages);
        if ($request->has('company')) { $user->company = $request->company; }
        if ($request->has('permits')) { $user->permits = $request->permits; }
        if ($request->has('permits_e')) { $user->permits_e = $request->permits_e; }
        if ($user->save()) { return $this->showOne($user); }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }

    public function changePassword(Request $request, User $user)
    {
        //if (!User::searchPermitOnArray("UE")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $rules = [
            'password' => 'required',
        ];
        $messages = [
            'password.required' => 'El campo password es obligatorio.',
        ];
        $this->validate($request, $rules, $messages);
        if ($request->has('password')) { $user->password = bcrypt($request->password); }
        $user->change_password = 1;
        /*if ($user->isClean()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }*/
        if ($user->save()) { return $this->showOne($user); }
        return $this->errorResponse("No se ha podido crear el registro.", 422);
    }

    public function checkPassword(Request $request, User $user)
    {
        //if (!User::searchPermitOnArray("UE")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $rules = [
            'password' => 'required',
        ];
        $messages = [
            'password.required' => 'El campo password es obligatorio.',
        ];
        $this->validate($request, $rules, $messages);
        if ($request->has('password')) {
            if (Hash::check($request->password, $user->password)) {
                return response()->json(['result' => true], 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            } else {
                return response()->json(['result' => false], 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }
        }
        return response()->json(['result' => false], 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function recoveryPassword(Request $request)
    {
        //if (!User::searchPermitOnArray("UE")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $rules = [
            'email' => 'required',
        ];
        $messages = [
            'email.required' => 'El campo correo electrónico es obligatorio.',
        ];
        $this->validate($request, $rules, $messages);
        if ($request->has('email')) {
            $user = User::where('email', $request->email)->first();
            if ($user != null) {
                $pass_temp = $this->generateCode(10);
                $user->password = bcrypt($pass_temp);
                $user->change_password = 0;
                $user->save();
                SendEmail::EmailRecoveryPassword($user->email, $pass_temp);
                return response()->json(['result' => true], 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            } else {
                return response()->json(['result' => false], 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }
        }
        return response()->json(['result' => false], 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }

    function generateCode($limit){
        $code = '';
        for($i = 0; $i < $limit; $i++) { $code .= mt_rand(0, 9); }
        return $code;
    }
}
