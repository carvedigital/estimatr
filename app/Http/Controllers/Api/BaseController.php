<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function respondWithArray($data, $header = 200)
    {
        return response()->json($data, $header);
    }

    public function respondSuccess()
    {
        return $this->respondWithArray(['success' => true], 200);
    }

    public function respondError($data, $header = 400)
    {
        return $this->respondWithArray(array_merge($data, ['success' => false]), $header);
    }
}
