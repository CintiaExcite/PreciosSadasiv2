<?php

namespace App\Http\Controllers\Api\UserPermit;

use App\User;
use App\UserPermit;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class UserPermitController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!User::searchPermitOnArray("U")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $user_permits = UserPermit::where('status', 1)->orderBy('state')->get();
        return $this->showAll($user_permits);
    }


    /**
     * Display a listing of the resource by company.
     *
     * @return \Illuminate\Http\Response
     */
    public function permitsByCompany(Request $request)
    {
        if (!User::searchPermitOnArray("U")) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        $user_permits = UserPermit::where([['status', 1], ['company', $request->company]])->orderBy('state')->get();
        return $this->showAll($user_permits);
    }

    
}
