<?php

namespace App\Http\Controllers\Api\Development;

use App\Models\Development;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class DevelopmentTokenController extends ApiController
{
    public function index(Development $development)
    {
        $token = $development->tokens()
            ->get();
        
        return $this->showAll($token);
    } 
}
