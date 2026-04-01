<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiClient;

class EspaciosController extends Controller
{
    protected $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function index()
    {
        $espacios = $this->apiClient->get('/espacios');
        if (isset($espacios['error'])) $espacios = [];
        return view('admin.espacios.index', compact('espacios'));
    }

    public function create()
    {
        return view('admin.espacios.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo_espacio' => 'required|string',
            'nombre' => 'required|string',
            'ubicacion' => 'required|string',
            'capacidad' => 'required|integer',
            'horario_apertura' => 'required',
            'horario_cierre' => 'required',
            'disponible' => 'sometimes|boolean'
        ]);

        $response = $this->apiClient->post('/espacios', $data);
        if (isset($response['error'])) {
            return back()->withErrors(['error' => 'Error al crear espacio: ' . ($response['message'] ?? '')]);
        }
        return redirect()->route('espacios.index')->with('success', 'Espacio creado correctamente');
    }

    public function edit($id)
    {
        $espacio = $this->apiClient->get("/espacios/{$id}");
        if (isset($espacio['error'])) {
            return redirect()->route('espacios.index')->withErrors(['error' => 'Espacio no encontrado']);
        }
        return view('admin.espacios.edit', compact('espacio'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'tipo_espacio' => 'sometimes|string',
            'nombre' => 'sometimes|string',
            'ubicacion' => 'sometimes|string',
            'capacidad' => 'sometimes|integer',
            'horario_apertura' => 'sometimes',
            'horario_cierre' => 'sometimes',
            'disponible' => 'sometimes|boolean'
        ]);

        $response = $this->apiClient->put("/espacios/{$id}", $data);
        if (isset($response['error'])) {
            return back()->withErrors(['error' => 'Error al actualizar espacio']);
        }
        return redirect()->route('espacios.index')->with('success', 'Espacio actualizado');
    }

    public function destroy($id)
    {
        $response = $this->apiClient->delete("/espacios/{$id}");
        if (isset($response['error'])) {
            return back()->withErrors(['error' => 'Error al eliminar espacio']);
        }
        return redirect()->route('espacios.index')->with('success', 'Espacio eliminado');
    }

    public function equipos($id)
    {
        $equipos = $this->apiClient->get("/espacios/{$id}/equipos");
        if (isset($equipos['error'])) $equipos = [];
        return response()->json($equipos);
    }


    public function disponibles()
    {
        $espacios = $this->apiClient->get('/espacios');
        if (isset($espacios['error'])) $espacios = [];

        $equipos = $this->apiClient->get('/equipos');
        if (isset($equipos['error'])) $equipos = [];

        $fecha_hoy = date('Y-m-d');

        return view('admin.sitios.disponibles', compact('espacios', 'equipos', 'fecha_hoy'));
    }

    public function ocupados()
    {
        $espacios = $this->apiClient->get('/espacios');
        if (isset($espacios['error'])) $espacios = [];

        $fecha_hoy = date('Y-m-d');

        return view('admin.sitios.ocupados', compact('espacios', 'fecha_hoy'));
    } 
}