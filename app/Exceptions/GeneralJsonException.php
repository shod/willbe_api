<?php

namespace App\Exceptions;

use \Illuminate\Http\JsonResponse;
use Exception;

class GeneralJsonException extends Exception
{
    //private $code = 422;
    /**
     * Render the exception as an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function render($request)
    {
        return new JsonResponse([
            'message' => $this->getMessage(),
            'success' => false
        ], $this->code);
    }
}
