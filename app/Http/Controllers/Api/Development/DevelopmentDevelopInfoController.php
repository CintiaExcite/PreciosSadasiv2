<?php

namespace App\Http\Controllers\Api\Development;

use App\Models\Development;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class DevelopmentDevelopInfoController extends ApiController
{
    public function index(Development $development)
    {
        $development_info = $development->development_info()
            ->get();

        return $this->showAll($development_info);
    }
}
