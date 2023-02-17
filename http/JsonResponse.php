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
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
        http_response_code($responseCode);
        return json_encode($attributes);
    }
}