from flask import Flask, render_template, redirect, url_for

app = Flask(__name__)

@app.route('/')
def index():
    return redirect(url_for('login'))

@app.route('/login')
def login():
    return render_template('login.html')

@app.route('/registro')
def registro():
    return render_template('registro.html')

@app.route('/dashboard')
def dashboard():
    # Renderiza el nuevo panel de control con las tarjetas (dashboard.html)
    return render_template('dashboard.html')

@app.route('/agendar')
def agendar():
    # Esta es la vista específica para ver horarios y agendar (agendar.html)
    horarios = [
        {'rango': '10:00 - 12:00', 'capacidad': '25 PCs disponibles'},
        {'rango': '12:00 - 14:00', 'capacidad': '30 PCs disponibles'}
    ]
    return render_template('agendar.html', horarios=horarios)

@app.route('/mis-reservas')
def mis_reservas():
    # Basado en tu tabla `reservas`
    reservas = [
        {'fecha': '2026-02-25', 'horario': '10:00 - 12:00', 'espacio': 'Laboratorio A1', 'estado': 'activa'},
        {'fecha': '2026-02-18', 'horario': '08:00 - 10:00', 'espacio': 'Laboratorio A2', 'estado': 'cancelada'}
    ]
    return render_template('mis_reservas.html', reservas=reservas)

@app.route('/eventos')
def eventos():
    # Basado en tu tabla `eventos`
    eventos_data = [
        {'nombre': 'Hackathon SAGE', 'descripcion': 'Competencia de programación.', 'fecha_inicio': '2026-03-25 08:00', 'fecha_fin': '2026-03-26 20:00'},
        {'nombre': 'Taller de Bases de Datos', 'descripcion': 'Introducción a MySQL y Laravel.', 'fecha_inicio': '2026-04-10 16:00', 'fecha_fin': '2026-04-10 18:00'}
    ]
    return render_template('eventos.html', eventos=eventos_data)

@app.route('/sitios')
def sitios():
    # Basado en tu tabla `espacios`
    sitios_data = [
        {'nombre': 'Laboratorio A1', 'tipo': 'Laboratorio Cómputo', 'ubicacion': 'Edificio A, Planta Baja', 'capacidad': 30, 'apertura': '07:00', 'cierre': '22:00'},
        {'nombre': 'Sala de Juntas 1', 'tipo': 'Sala de Reuniones', 'ubicacion': 'Edificio B, Nivel 2', 'capacidad': 10, 'apertura': '08:00', 'cierre': '20:00'}
    ]
    return render_template('sitios.html', sitios=sitios_data)

if __name__ == '__main__':
    app.run(debug=True, port=7000)