<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiClient;

class ClasesController extends Controller
{
    protected $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function index()
    {
        $clases = $this->apiClient->get('/clases');
        if (isset($clases['error'])) $clases = [];
        return view('admin.clases.index', compact('clases'));
    }

    public function create()
    {
        return view('admin.clases.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'materia' => 'required|string',
            'grupo' => 'required|string',
            'docente' => 'required|string',
            'correo_docente' => 'nullable|email'
        ]);

        $response = $this->apiClient->post('/clases', $data);
        if (isset($response['error'])) {
            return back()->withErrors(['error' => 'Error al crear clase']);
        }
        return redirect()->route('clases.index')->with('success', 'Clase creada');
    }

    public function edit($id)
    {
        $clase = $this->apiClient->get("/clases/{$id}");
        if (isset($clase['error'])) {
            return redirect()->route('clases.index')->withErrors(['error' => 'Clase no encontrada']);
        }
        return view('admin.clases.edit', compact('clase'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|string',
            'materia' => 'sometimes|string',
            'grupo' => 'sometimes|string',
            'docente' => 'sometimes|string',
            'correo_docente' => 'nullable|email'
        ]);

        $response = $this->apiClient->put("/clases/{$id}", $data);
        if (isset($response['error'])) {
            return back()->withErrors(['error' => 'Error al actualizar clase']);
        }
        return redirect()->route('clases.index')->with('success', 'Clase actualizada');
    }

    public function destroy($id)
    {
        $response = $this->apiClient->delete("/clases/{$id}");
        if (isset($response['error'])) {
            return back()->withErrors(['error' => 'Error al eliminar clase']);
        }
        return redirect()->route('clases.index')->with('success', 'Clase eliminada');
    }
}