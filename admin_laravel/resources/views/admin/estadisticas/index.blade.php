@extends('layouts.app')

@section('content')
    <div>
        <h1 class="title-outline">REPORTES SAGE</h1>
        
        <div style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
            <button class="neon-box" style="background: var(--neon-blue); color: var(--bg-dark); padding: 10px 20px; font-weight: bold; border: none; cursor: pointer; border-radius: 8px;">
                📥 Descargar PDF
            </button>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 20px;">
            
            <div class="neon-box" style="padding: 30px; text-align: center;">
                <h3 style="color: var(--neon-blue); margin-top: 0;">Sitios Más Reservados</h3>
                <p style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 20px;">Por estudiantes este mes</p>
                
                <div style="background-color: #1a2333; height: 250px; display: flex; align-items: flex-end; justify-content: space-evenly; padding: 20px; border-radius: 8px;">
                    <div style="width: 40px; height: 95%; background: #3b82f6; border-radius: 4px 4px 0 0; position: relative;"><span style="position: absolute; top: -25px; left: 5px; color: white; font-size: 0.8rem;">A1</span></div>
                    <div style="width: 40px; height: 70%; background: #60a5fa; border-radius: 4px 4px 0 0; position: relative;"><span style="position: absolute; top: -25px; left: 5px; color: white; font-size: 0.8rem;">B2</span></div>
                    <div style="width: 40px; height: 45%; background: #93c5fd; border-radius: 4px 4px 0 0; position: relative;"><span style="position: absolute; top: -25px; left: 5px; color: white; font-size: 0.8rem;">A2</span></div>
                </div>
            </div>

            <div class="neon-box" style="padding: 30px; text-align: center;">
                <h3 style="color: var(--neon-blue); margin-top: 0;">Horarios Más Solicitados</h3>
                <p style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 20px;">Distribución de carga de red/espacios</p>
                
                <div style="background-color: #1a2333; height: 250px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                    <div style="width: 180px; height: 180px; border-radius: 50%; background: conic-gradient(#3b82f6 0% 45%, #60a5fa 45% 75%, #bfdbfe 75% 100%); position: relative;">
                        <div style="position: absolute; top: 30px; right: -50px; background: #0b101a; padding: 5px 10px; border-radius: 5px; font-size: 0.8rem; border: 1px solid #3b82f6;">10:00 - 12:00 (45%)</div>
                        <div style="position: absolute; bottom: 30px; left: -50px; background: #0b101a; padding: 5px 10px; border-radius: 5px; font-size: 0.8rem; border: 1px solid #60a5fa;">14:00 - 16:00 (30%)</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection