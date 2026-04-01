from flask import Flask, render_template, redirect, url_for, request, flash, session, jsonify
from datetime import date, datetime, time
from config import Config
from services import api_request

app = Flask(__name__)
app.config.from_object(Config)

# ============================================
# MÓDULOS PREDEFINIDOS (fallback si FastAPI no responde)
# ============================================
DEFAULT_MODULOS = [
    {"nombre": "Módulo 1 (7:00-8:40)", "hora_inicio": "07:00", "hora_fin": "08:40"},
    {"nombre": "Módulo 2 (8:40-10:20)", "hora_inicio": "08:40", "hora_fin": "10:20"},
    {"nombre": "Módulo 3 (10:20-12:00)", "hora_inicio": "10:20", "hora_fin": "12:00"},
    {"nombre": "Módulo 4 (12:00-13:40)", "hora_inicio": "12:00", "hora_fin": "13:40"},
    {"nombre": "Módulo 5 (14:00-15:40)", "hora_inicio": "14:00", "hora_fin": "15:40"},
    {"nombre": "Módulo 6 (15:40-17:20)", "hora_inicio": "15:40", "hora_fin": "17:20"},
    {"nombre": "Módulo 7 (17:20-19:00)", "hora_inicio": "17:20", "hora_fin": "19:00"},
    {"nombre": "Módulo 8 (19:00-20:40)", "hora_inicio": "19:00", "hora_fin": "20:40"},
]

# ============================================
# RUTAS DE ACCESO
# ============================================

@app.route('/')
def index():
    return redirect(url_for('login'))

@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        email = request.form.get('email')
        password = request.form.get('password')
        role = 'estudiante'

        data = {'username': email, 'password': password, 'scope': role}
        resp = api_request('/auth/login', method='POST', data=data)

        if not isinstance(resp, dict) or 'error' in resp:
            flash('Credenciales inválidas o error de conexión con la API', 'danger')
            return redirect(url_for('login'))

        session['access_token'] = resp.get('access_token')
        session['user_role'] = role
        session['user_email'] = email

        # Obtener datos completos del estudiante (opcional)
        user_info = api_request('/users/me', method='GET')
        if isinstance(user_info, dict) and not user_info.get('error'):
            session['user_id'] = user_info.get('id')
            session['user_name'] = user_info.get('nombre_completo', 'Estudiante')
            session['user_career'] = user_info.get('carrera', 'No especificada')
            session['user_matricula'] = user_info.get('matricula', 'N/D')

        return redirect(url_for('dashboard'))

    return render_template('login.html')

@app.route('/registro', methods=['GET', 'POST'])
def registro():
    if request.method == 'POST':
        contrasena = request.form.get('contrasena')
        confirmar = request.form.get('confirmar')
        if contrasena != confirmar:
            flash('Las contraseñas no coinciden.', 'danger')
            return redirect(url_for('registro'))

        data = {
            'nombre_completo': request.form.get('nombre_completo'),
            'matricula': request.form.get('matricula'),
            'correo': request.form.get('correo'),
            'carrera': request.form.get('carrera'),
            'contrasena': contrasena
        }
        resp = api_request('/auth/registro', method='POST', json=data)
        if 'error' in resp:
            flash('Error en registro: ' + resp.get('detail', 'Ocurrió un error en la API'), 'danger')
            return redirect(url_for('registro'))
        flash('Registro exitoso. ¡Ahora puedes iniciar sesión!', 'success')
        return redirect(url_for('login'))
    return render_template('registro.html')

@app.route('/dashboard')
def dashboard():
    if 'access_token' not in session:
        return redirect(url_for('login'))
    reservas = api_request('/reservas', method='GET')
    total_reservas = 0
    if isinstance(reservas, list):
        total_reservas = sum(1 for r in reservas if r.get('estado') == 'activa')
    eventos = api_request('/eventos/proximos', method='GET')
    total_eventos = len(eventos) if isinstance(eventos, list) else 0
    return render_template('dashboard.html',
                           user=session,
                           total_reservas=total_reservas,
                           total_eventos=total_eventos)

# ============================================
# RESERVAS (ESTUDIANTES)
# ============================================

@app.route('/agendar')
def agendar():
    if 'access_token' not in session:
        return redirect(url_for('login'))

    espacios = api_request('/espacios', method='GET')
    if not isinstance(espacios, list):
        espacios = []

    equipos = api_request('/equipos', method='GET')
    if not isinstance(equipos, list):
        equipos = []

    fecha_hoy = date.today().strftime('%Y-%m-%d')
    return render_template('agendar.html', espacios=espacios, equipos=equipos, fecha_hoy=fecha_hoy)

@app.route('/agendar-confirmar', methods=['POST'])
def agendar_confirmar():
    if 'access_token' not in session:
        return redirect(url_for('login'))

    id_espacio = request.form.get('id_espacio', '').strip()
    id_equipo = request.form.get('id_equipo', '').strip()
    fecha = request.form.get('fecha', '').strip()
    bloque = request.form.get('bloque_horario', '').strip()

    if not id_espacio or not id_equipo or not bloque:
        flash('Error: No se seleccionó correctamente el equipo o el horario.', 'danger')
        return redirect(url_for('agendar'))

    # Validar fecha futura
    try:
        fecha_reserva = datetime.strptime(fecha, '%Y-%m-%d').date()
        if fecha_reserva < date.today():
            flash('No se pueden reservar fechas pasadas.', 'danger')
            return redirect(url_for('agendar'))
    except:
        flash('Fecha inválida.', 'danger')
        return redirect(url_for('agendar'))

    try:
        hora_i, hora_f = bloque.split('-')
        data = {
            "id_espacio": int(id_espacio),
            "id_equipo": int(id_equipo),
            "tipo_reserva": "estudiante",
            "fecha_hora_inicio": f"{fecha}T{hora_i}:00",
            "fecha_hora_fin": f"{fecha}T{hora_f}:00"
        }
        # Añadir el campo que FastAPI espera para el beneficiario
        # Si FastAPI requiere id_estudiante_beneficiario, lo añadimos
        data['id_estudiante_beneficiario'] = session.get('user_id')
        
        # Intentar primero con el endpoint específico de estudiante
        resp = api_request('/reservas/estudiante', method='POST', json=data)
        if isinstance(resp, dict) and 'error' in resp:
            # Si falla, probar con el endpoint general (por si no existe el específico)
            resp = api_request('/reservas', method='POST', json=data)

        if isinstance(resp, dict) and 'error' in resp:
            msg = resp.get('detail', 'El horario ya no está disponible.')
            flash(f"No disponible: {msg}", 'danger')
            return redirect(url_for('agendar'))

        flash('¡Reserva confirmada con éxito!', 'success')
        return redirect(url_for('mis_reservas'))
    except Exception as e:
        flash(f'Error interno: {str(e)}', 'danger')
        return redirect(url_for('agendar'))

@app.route('/mis-reservas')
def mis_reservas():
    if 'access_token' not in session:
        return redirect(url_for('login'))

    # Obtener reservas del estudiante (el token ya filtra por usuario)
    reservas = api_request('/reservas', method='GET')
    # Manejo de diferentes estructuras de respuesta
    if isinstance(reservas, dict):
        # Podría venir como {'data': [...]} o {'reservas': [...]}
        if 'data' in reservas:
            reservas = reservas['data']
        elif 'reservas' in reservas:
            reservas = reservas['reservas']
        else:
            reservas = []
    elif not isinstance(reservas, list):
        reservas = []

    return render_template('mis_reservas.html', reservas=reservas)

@app.route('/cancelar-reserva/<int:reserva_id>', methods=['POST'])
def cancelar_reserva(reserva_id):
    if 'access_token' not in session:
        return redirect(url_for('login'))

    motivo = request.form.get('motivo', 'Cancelada por el estudiante')
    # Endpoint de cancelación en FastAPI (asume PUT /reservas/{id}/cancelar)
    resp = api_request(f'/reservas/{reserva_id}/cancelar', method='PUT', json={'motivo': motivo})

    if isinstance(resp, dict) and 'error' in resp:
        flash('No se pudo cancelar la reserva.', 'danger')
    else:
        flash('Reserva cancelada correctamente.', 'success')

    return redirect(url_for('mis_reservas'))

# ============================================
# EVENTOS Y ESPACIOS (INFORMACIÓN)
# ============================================

@app.route('/eventos')
def eventos():
    if 'access_token' not in session:
        return redirect(url_for('login'))
    eventos = api_request('/eventos/proximos', method='GET')
    if not isinstance(eventos, list):
        eventos = []
    return render_template('eventos.html', eventos=eventos)

@app.route('/sitios')
def sitios():
    if 'access_token' not in session:
        return redirect(url_for('login'))
    espacios = api_request('/espacios', method='GET')
    if not isinstance(espacios, list):
        espacios = []
    return render_template('sitios.html', sitios=espacios)

@app.route('/espacios')
def espacios_info():
    if 'access_token' not in session:
        return redirect(url_for('login'))
    espacios = api_request('/espacios', method='GET')
    if not isinstance(espacios, list):
        espacios = []
    return render_template('espacios.html', espacios=espacios)

# ============================================
# ENDPOINTS AUXILIARES (AJAX) CON FALLBACK
# ============================================

@app.route('/api/ocupacion', methods=['GET'])
def api_ocupacion():
    if 'access_token' not in session:
        return jsonify({'error': 'No autorizado'}), 401

    fecha = request.args.get('fecha')
    if not fecha:
        return jsonify({'error': 'Fecha no proporcionada'}), 400

    reservas = api_request(f'/reservas?fecha={fecha}', method='GET')
    if not isinstance(reservas, list):
        reservas = []

    ocupacion = {}
    for res in reservas:
        eq_id = res.get('id_equipo')
        if eq_id:
            hora_uso = res.get('fecha_hora_inicio', '').split('T')[1][:5]
            if eq_id not in ocupacion:
                ocupacion[eq_id] = []
            ocupacion[eq_id].append(hora_uso)

    return jsonify({'ocupacion': ocupacion, 'fecha': fecha})

@app.route('/api/equipos/<int:equipo_id>/modulos')
def api_modulos_disponibles(equipo_id):
    if 'access_token' not in session:
        return jsonify({'error': 'No autorizado'}), 401

    fecha = request.args.get('fecha')
    if not fecha:
        return jsonify({'error': 'Falta fecha'}), 400

    # Validar que la fecha no sea pasada
    try:
        fecha_obj = datetime.strptime(fecha, '%Y-%m-%d').date()
        if fecha_obj < date.today():
            return jsonify({'error': 'No se pueden reservar fechas pasadas'}), 400
    except:
        pass

    # Obtener módulos desde FastAPI (con fallback)
    modulos = api_request('/modulos', method='GET')
    if not isinstance(modulos, list) or len(modulos) == 0:
        modulos = DEFAULT_MODULOS  # usar los predefinidos

    # Obtener reservas activas del equipo en esa fecha
    reservas = api_request(f'/reservas?equipo_id={equipo_id}&fecha={fecha}', method='GET')
    if not isinstance(reservas, list):
        reservas = []

    ocupados = set()
    for r in reservas:
        hora = r.get('fecha_hora_inicio', '').split('T')[1][:5]
        ocupados.add(hora)

    disponibles = []
    for m in modulos:
        if m.get('hora_inicio') not in ocupados:
            disponibles.append({
                'nombre': m.get('nombre'),
                'hora_inicio': m.get('hora_inicio'),
                'hora_fin': m.get('hora_fin')
            })

    return jsonify({'modulos': disponibles})

# ============================================
# LOGOUT
# ============================================

@app.route('/logout')
def logout():
    session.clear()
    return redirect(url_for('login'))

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5040, debug=True)