<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Set Global Response
     *
     * @param Boolean $success
     * @param String $message
     * @param Array $data
     * @param Integer $status
     *
     * @return JSON
     */
    public function sendResponse($success, $message, $data = [])
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $data,
        ], Response::HTTP_OK);
    }
}
