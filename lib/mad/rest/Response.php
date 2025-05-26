<?php

namespace Mad\Rest;

/**
 * Response
 * 
 * Main Response class for handling HTTP responses
 */
class Response
{
    /**
     * Create a JSON response
     * 
     * @param mixed $result The result to be returned
     * @param int $code HTTP status code
     * @return string JSON string
     */
    public function json($result, $code = 200)
    {
        $jsonResponse = new JSONResponse($result, $code);

        return $jsonResponse->parse();
    }
}