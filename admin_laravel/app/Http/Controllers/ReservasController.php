<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiClient;
use Illuminate\Support\Facades\Log;

class ReservasController extends Controller
{
    protected $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function index(Request $request)
    {
        $espacioId = $request->query('espacio_id');
        $fecha = $request->query('fecha');

        $params = ['estado' => 'activa'];
        if ($espacioId) {
            $params['espacio_id'] = $espacioId;
        }
        if ($fecha) {
            $params['fecha'] = $fecha;
        }

        $reservas = $this->apiClient->get('/reservas', $params);
        if (isset($reservas['error'])) {
            Log::error('Error al obtener reservas: ' . $reservas['message']);
            return response()->json(['error' => $reservas['message']], 500);
        }

        // Enriquecer cada reserva con los datos del beneficiario
        foreach ($reservas as &$res) {
            switch ($res['tipo_reserva']) {
                case 'estudiante':
                    if (!empty($res['id_estudiante'])) {
                        $estudiante = $this->apiClient->get("/estudiantes/{$res['id_estudiante']}");
                        if (!isset($estudiante['error'])) {
                            $res['estudiante_beneficiario'] = [
                                'nombre_completo' => $estudiante['nombre_completo'] ?? 'Desconocido'
                            ];
                        }
                    }
                    break;
                case 'clase':
                    if (!empty($res['id_clase'])) {
                        $clase = $this->apiClient->get("/clases/{$res['id_clase']}");
                        if (!isset($clase['error'])) {
                            $res['clase_beneficiario'] = [
                                'nombre' => $clase['nombre'] ?? 'Desconocido'
                            ];
                        }
                    }
                    break;
                case 'evento':
                    if (!empty($res['id_evento'])) {
                        $evento = $this->apiClient->get("/eventos/{$res['id_evento']}");
                        if (!isset($evento['error'])) {
                            $res['evento_beneficiario'] = [
                                'nombre' => $evento['nombre'] ?? 'Desconocido'
                            ];
                        }
                    }
                    break;
            }
        }

        return response()->json($reservas);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_espacio' => 'required|integer',
            'id_equipo' => 'required|integer',
            'tipo_reserva' => 'required|in:estudiante,clase,evento',
            'id_referencia' => 'required|integer',
            'fecha_hora_inicio' => 'required|date',
            'fecha_hora_fin' => 'required|date|after:fecha_hora_inicio',
            'recurrencia' => 'sometimes|in:una_vez,semanal'
        ]);

        $recurrencia = $data['recurrencia'] ?? 'una_vez';

        $payload = [
            'id_espacio' => $data['id_espacio'],
            'id_equipo' => $data['id_equipo'],
            'tipo_reserva' => $data['tipo_reserva'],
            'fecha_hora_inicio' => $data['fecha_hora_inicio'],
            'fecha_hora_fin' => $data['fecha_hora_fin']
        ];

        switch ($data['tipo_reserva']) {
            case 'estudiante':
                $payload['id_estudiante_beneficiario'] = $data['id_referencia'];
                break;
            case 'clase':
                $payload['id_clase_beneficiario'] = $data['id_referencia'];
                break;
            case 'evento':
                $payload['id_evento_beneficiario'] = $data['id_referencia'];
                break;
        }

        if ($recurrencia === 'semanal' && $data['tipo_reserva'] === 'clase') {
            $fechas = $this->generarFechasSemanales($data['fecha_hora_inicio'], 16);
            $response = $this->apiClient->post('/reservas/recurrentes', [
                'reservas' => array_map(function ($fecha) use ($payload) {
                    $nueva = $payload;
                    $nueva['fecha_hora_inicio'] = $fecha;
                    $inicioOriginal = new \DateTime($payload['fecha_hora_inicio']);
                    $finOriginal = new \DateTime($payload['fecha_hora_fin']);
                    $duracion = $inicioOriginal->diff($finOriginal);
                    $nuevaFechaFin = (new \DateTime($fecha))->add($duracion);
                    $nueva['fecha_hora_fin'] = $nuevaFechaFin->format('Y-m-d H:i:s');
                    return $nueva;
                }, $fechas)
            ]);
        } else {
            $response = $this->apiClient->post('/reservas', $payload);
        }

        if (isset($response['error'])) {
            return response()->json(['error' => true, 'message' => $response['message']], 400);
        }

        return response()->json(['success' => true]);
    }

    public function cancelar($id, Request $request)
    {
        $motivo = $request->input('motivo', 'Cancelada por administrador');
        $response = $this->apiClient->put("/reservas/{$id}/cancelar", ['motivo' => $motivo]);
        if (isset($response['error'])) {
            return response()->json(['error' => true, 'message' => $response['message']], 400);
        }
        return response()->json(['success' => true]);
    }

    public function ocupacion(Request $request)
    {
        $fecha = $request->query('fecha');
        if (!$fecha) {
            return response()->json(['error' => 'Fecha no proporcionada'], 400);
        }

        $reservas = $this->apiClient->get('/reservas', ['fecha' => $fecha]);
        if (isset($reservas['error'])) {
            return response()->json(['error' => $reservas['message']], 500);
        }

        $ocupacion = [];
        foreach ($reservas as $res) {
            $equipoId = $res['id_equipo'] ?? null;
            if ($equipoId) {
                $hora = substr($res['fecha_hora_inicio'], 11, 5);
                if (!isset($ocupacion[$equipoId])) {
                    $ocupacion[$equipoId] = [];
                }
                $ocupacion[$equipoId][] = $hora;
            }
        }
        return response()->json(['ocupacion' => $ocupacion]);
    }

    private function generarFechasSemanales($fechaInicio, $semanas)
    {
        $fechas = [];
        $inicio = new \DateTime($fechaInicio);
        for ($i = 0; $i < $semanas; $i++) {
            $fechas[] = $inicio->format('Y-m-d H:i:s');
            $inicio->modify('+1 week');
        }
        return $fechas;
    }
}