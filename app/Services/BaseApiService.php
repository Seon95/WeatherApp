<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

abstract class BaseApiService
{
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    protected function get($url, $headers = [])
    {
        $response = Http::withHeaders(array_merge([
            'api_key' => $this->apiKey
        ], $headers))->get($url);

        if (!$response->successful()) {
            throw new \Exception('Error in API request: ' . $response->status());
        }

        return $response->json();
    }
}
