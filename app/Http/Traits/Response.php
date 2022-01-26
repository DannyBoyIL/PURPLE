<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait Response {

    /**
     * success response method.
     *
     * @param $result
     * @param $message
     * @param int $status
     * @return JsonResponse
     */
    public function sendResponse($result, $message, $status = 200): JsonResponse {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];
        return response()->json($response, $status);
    }

    /**
     * return error response.
     *
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($error, $errorMessages = [], $code = 404): JsonResponse {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }

}
