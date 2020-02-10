<?php

namespace App\Http\Controllers\Api\State;

use App\User;
use App\Models\Log;
use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class StateDevelopmentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(State $state)
    {
        if (!User::searchPermitOnArray(["E", $state->id])) return $this->errorResponse("No tienes permitido realizar esta acción.", 401);
        if (User::searchPermitOnArray("E")) {
            $developments = $state->developments()
                ->where('developments.status', 1)
                ->get();
        } else {
            $permits_e = User::permitsEArray();
    		$developments = $state->developments()
                ->where('developments.status', 1)
                ->whereIn('developments.id', $permits_e)
                ->get();
        }
        return $this->showAll($developments);
    }
}
