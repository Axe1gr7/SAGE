<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiClient;

class EventosController extends Controller
{
    protected $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function index()
    {
        $eventos = $this->apiClient->get('/eventos');
        if (isset($eventos['error'])) $eventos = [];
        return view('admin.eventos.index', compact('eventos'));
    }

    public function create()
    {
        return view('admin.eventos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio'
        ]);

        $response = $this->apiClient->post('/eventos', $data);
        if (isset($response['error'])) {
            return back()->withErrors(['error' => 'Error al crear evento']);
        }
        return redirect()->route('eventos.index')->with('success', 'Evento creado');
    }

    public function edit($id)
    {
        $evento = $this->apiClient->get("/eventos/{$id}");
        if (isset($evento['error'])) {
            return redirect()->route('eventos.index')->withErrors(['error' => 'Evento no encontrado']);
        }
        return view('admin.eventos.edit', compact('evento'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|string',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio'
        ]);

        $response = $this->apiClient->put("/eventos/{$id}", $data);
        if (isset($response['error'])) {
            return back()->withErrors(['error' => 'Error al actualizar evento']);
        }
        return redirect()->route('eventos.index')->with('success', 'Evento actualizado');
    }

    public function destroy($id)
    {
        $response = $this->apiClient->delete("/eventos/{$id}");
        if (isset($response['error'])) {
            return back()->withErrors(['error' => 'Error al eliminar evento']);
        }
        return redirect()->route('eventos.index')->with('success', 'Evento eliminado');
    }
}