<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(title="Moadcars api integration", version="0.1")
 *
 * @OAS\SecurityScheme(
 *      securityScheme="apiKey",
 *      type="http",
 *      scheme="bearer"
 * )
 */

class BaseController extends Controller
{

    public function sendResponse($result, $message, $pagination = [])
    {
       
        $response = [
            'data'    => $result,
            'message' => $message
        ];

        if (count($pagination) > 0) {
            $response['pagination'] = $pagination;
        }

        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            //'success' => false,
            'error' => $error,
        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
}
