<?php

namespace App\Http\Controllers;

use InfyOm\Generator\Utils\ResponseUtil;

 
/**
* @OA\Swagger(
*   basePath="/api",
*   @OA\Info(
*     title="PSS APIs",
*     version="1.0.0",
*   )
* )
*  @OA\Server(
*      url=API_LOCAL_HOST,
*      description="LOCAL API Server"
* ),
*  @OA\Server(
*      url=API_LIVE_HOST,
*      description="LIVE API Server"
* ),
*   @OA\SecurityScheme(
*      securityScheme="bearerAuth",
*      in="header",
*      name="bearerAuth",
*      type="http",
*      scheme="bearer",
*      bearerFormat="Passport",
* ),
* This class should be parent class for other API controllers
* Class AppBaseController
*/
class AppBaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        return response()->json(ResponseUtil::makeResponse($message, $result));
    }

    public function sendError($error, $code = 404)
    {
        return response()->json(ResponseUtil::makeError($error), $code);
    }

    public function sendSuccess($message)
    {
        return response()->json([
            'success' => true,
            'message' => $message
        ], 200);
    }
}
