<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GestionController extends Controller
{
    protected $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    protected function getEndpoint($entidad)
    {
        $map = [
            'administradores' => '/administradores',
            'estudiantes'     => '/estudiantes',
            'clases'          => '/clases',
            'eventos'         => '/eventos',
        ];
        return $map[$entidad] ?? null;
    }

    /**
     * Obtener listado de registros (API).
     */
    public function index($entidad)
    {
        $endpoint = $this->getEndpoint($entidad);
        if (!$endpoint) {
            return response()->json(['error' => 'Entidad no válida'], 404);
        }

        $data = $this->apiClient->get($endpoint);

        if (isset($data['error'])) {
            Log::error("Error en GestionController@index: {$data['message']}");
            return response()->json(['error' => $data['message']], 500);
        }

        // Si es un array plano, devolverlo
        if (is_array($data)) {
            return response()->json($data);
        }

        // Si es un objeto, extraer la lista
        if (is_object($data)) {
            $data = (array) $data;
            if (isset($data['data'])) {
                return response()->json($data['data']);
            }
            if (isset($data['items'])) {
                return response()->json($data['items']);
            }
            return response()->json($data);
        }

        return response()->json([]);
    }

    /**
     * Obtener un solo registro.
     */
    public function show($entidad, $id)
    {
        $endpoint = $this->getEndpoint($entidad);
        if (!$endpoint) {
            return response()->json(['error' => 'Entidad no válida'], 404);
        }

        $data = $this->apiClient->get($endpoint . '/' . $id);
        return response()->json($data);
    }

    /**
     * Crear un registro.
     */
    public function store(Request $request, $entidad)
    {
        $endpoint = $this->getEndpoint($entidad);
        if (!$endpoint) {
            return response()->json(['error' => 'Entidad no válida'], 404);
        }

        $data = $request->all();

        if (in_array($entidad, ['administradores', 'estudiantes'])) {
            if (empty($data['contrasena'])) {
                unset($data['contrasena']);
            }
        }

        $response = $this->apiClient->post($endpoint, $data);
        return response()->json($response);
    }

    /**
     * Actualizar un registro.
     */
    public function update(Request $request, $entidad, $id)
    {
        $endpoint = $this->getEndpoint($entidad);
        if (!$endpoint) {
            return response()->json(['error' => 'Entidad no válida'], 404);
        }

        $data = $request->all();

        if (in_array($entidad, ['administradores', 'estudiantes'])) {
            if (empty($data['contrasena'])) {
                unset($data['contrasena']);
            }
        }

        $response = $this->apiClient->put($endpoint . '/' . $id, $data);
        return response()->json($response);
    }

    /**
     * Eliminar un registro (soft delete).
     */
    public function destroy($entidad, $id)
    {
        $endpoint = $this->getEndpoint($entidad);
        if (!$endpoint) {
            return response()->json(['error' => 'Entidad no válida'], 404);
        }

        $response = $this->apiClient->delete($endpoint . '/' . $id);
        return response()->json($response);
    }
}