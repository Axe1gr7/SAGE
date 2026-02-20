@extends('layouts.app')

@section('content')
    <div>
        <h1 class="title-outline">SITIOS ACTIVOS</h1>
        
        <div class="capsule-menu">
            <a href="#" class="neon-box capsule-btn">LABORATORIO</a>
            <a href="#" class="neon-box capsule-btn">SALAS</a>
            <a href="#" class="neon-box capsule-btn">TALLERES</a>
            <a href="#" class="neon-box capsule-btn">BIBLIOTECA</a>
            <a href="#" class="neon-box capsule-btn">AUDITORIOS</a>
        </div>

        <div class="neon-box" style="padding: 2px; overflow: hidden;">
            <table class="neon-table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Ubicación</th>
                        <th>Descripción</th>
                        <th>Usuario</th>
                        <th>Fecha/hora</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>A1</td>
                        <td>monitor roto</td>
                        <td>122043202</td>
                        <td>10/1/26 11:10</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>PESADOS</td>
                        <td>aceite en el piso</td>
                        <td>122047653</td>
                        <td>10/1/26 11:05</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection