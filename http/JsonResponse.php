<?php

namespace http;

class JsonResponse
{
    /**
     * encode attributes and return json
     * @param int $responseCode
     * @param array|null $attributes
     * @return false|string
     */
    public function response(int $responseCode, array $attributes = null)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($responseCode);
        return json_encode($attributes);
    }
}