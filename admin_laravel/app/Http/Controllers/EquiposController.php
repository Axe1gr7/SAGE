<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiClient;

class EquiposController extends Controller
{
    protected $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    // Listar equipos de un espacio (para la vista de configuración)
    public function index(Request $request)
    {
        $espacioId = $request->query('espacio_id');
        if ($espacioId) {
            $equipos = $this->apiClient->get("/espacios/{$espacioId}/equipos");
        } else {
            $equipos = $this->apiClient->get('/equipos');
        }
        if (isset($equipos['error'])) $equipos = [];
        return response()->json($equipos);
    }

    // Cambiar estado operativo
    public function updateEstado(Request $request, $id)
    {
        $data = $request->validate([
            'estado_operativo' => 'required|in:operativo,mantenimiento',
            'motivo' => 'nullable|string'
        ]);

        $response = $this->apiClient->put("/equipos/{$id}/estado", $data);
        if (isset($response['error'])) {
            return response()->json(['error' => true, 'message' => $response['message']], 400);
        }
        return response()->json($response);
    }
}