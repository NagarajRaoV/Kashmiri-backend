<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Sending success response
     * @param array $response
     * @return mixed
     */
    public function successResponse(array $response, $code = 200)
    {
        return response()->json(array_merge(
            ['status' => $code, 'message' => 'success'], $response
        ));
    }


    /**
     * Sending error response
     * @param string $message
     * @return mixed
     */
    public function errorResponse($response)
    {
        return response()->json(
            [
                'status' => 401,
                'message' => 'failed',
                'data' => $response,
            ]
        );
    }

    /**
     * Convert an array into a stdClass()
     *
     * @param   array $array The array we want to convert
     *
     * @return  object
     */
    public function arrayToObject($array)
    {
        // First we convert the array to a json string
        $json = json_encode($array);

        // The we convert the json string to a stdClass()
        $object = json_decode($json);

        return $object;
    }
}
