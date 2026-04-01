<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ApiClient
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = env('API_URL', 'http://api_sage:8000');
        $this->token = Session::get('api_token');
    }

    protected function headers()
    {
        $headers = ['Accept' => 'application/json'];
        if ($this->token) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }
        return $headers;
    }

    public function get($endpoint, $params = [])
    {
        $response = Http::withHeaders($this->headers())
                        ->get($this->baseUrl . $endpoint, $params);
        return $this->handleResponse($response);
    }

    public function post($endpoint, $data = [])
    {
        $response = Http::withHeaders($this->headers())
                        ->post($this->baseUrl . $endpoint, $data);
        return $this->handleResponse($response);
    }

    // NUEVA FUNCIÓN: Envía los datos como Formulario (Requerido por FastAPI para el Login)
    public function postForm($endpoint, $data = [])
    {
        $response = Http::withHeaders($this->headers())
                        ->asForm() // <--- Convierte la petición a Formulario Web
                        ->post($this->baseUrl . $endpoint, $data);
        return $this->handleResponse($response);
    }

    public function put($endpoint, $data = [])
    {
        $response = Http::withHeaders($this->headers())
                        ->put($this->baseUrl . $endpoint, $data);
        return $this->handleResponse($response);
    }

    public function delete($endpoint)
    {
        $response = Http::withHeaders($this->headers())
                        ->delete($this->baseUrl . $endpoint);
        return $this->handleResponse($response);
    }

    protected function handleResponse($response)
    {
        if ($response->successful()) {
            return $response->json();
        }
        return [
            'error' => true,
            'status' => $response->status(),
            'message' => $response->body()
        ];
    }
}