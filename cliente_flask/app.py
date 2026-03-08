from flask import Flask, render_template, redirect, url_for

app = Flask(__name__)

# --- RUTAS DE ACCESO ---

@app.route('/')
def index():
    """Redirige al login por defecto"""
    return redirect(url_for('login'))

@app.route('/login')
def login():
    return render_template('login.html')

@app.route('/registro')
def registro():
    return render_template('registro.html')

# --- RUTAS DEL PORTAL (DASHBOARD) ---

@app.route('/dashboard')
def dashboard():
    """Panel principal con las tarjetas de navegación"""
    return render_template('dashboard.html')

@app.route('/agendar')
def agendar():
    """Vista interactiva con el mapa de nodos (PCs)"""
    horarios = [
        {'rango': '07:00 - 09:00', 'capacidad': '15 Libres'},
        {'rango': '09:00 - 11:00', 'capacidad': '10 Libres'},
        {'rango': '11:00 - 13:00', 'capacidad': '22 Libres'}
    ]
    return render_template('agendar.html', horarios=horarios)

@app.route('/mis-reservas')
def mis_reservas():
    """Historial de actividad del estudiante"""
    reservas_data = [
        {'fecha': '2026-03-10', 'horario': '09:00 - 11:00', 'espacio': 'LABORATORIO A1', 'estado': 'activa'},
        {'fecha': '2026-03-05', 'horario': '14:00 - 16:00', 'espacio': 'SALA DE JUNTAS 1', 'estado': 'cancelada'},
        {'fecha': '2026-02-28', 'horario': '10:00 - 12:00', 'espacio': 'LABORATORIO A1', 'estado': 'activa'}
    ]
    return render_template('mis_reservas.html', reservas=reservas_data)

@app.route('/eventos')
def eventos():
    """Noticias y eventos de la universidad"""
    eventos_data = [
        {
            'nombre': 'Hackathon SAGE 2026', 
            'descripcion': 'Desarrollo de microsistemas en 24 horas. ¡Premios en efectivo!', 
            'fecha_inicio': '25 de Marzo - 08:00', 
            'fecha_fin': '26 de Marzo - 20:00'
        },
        {
            'nombre': 'Taller: Docker & Flask', 
            'descripcion': 'Aprende a desplegar tus proyectos escolares en contenedores profesionales.', 
            'fecha_inicio': '10 de Abril - 16:00', 
            'fecha_fin': '10 de Abril - 19:00'
        }
    ]
    return render_template('eventos.html', eventos=eventos_data)

@app.route('/sitios')
def sitios():
    """Directorio de edificios y horarios generales"""
    sitios_data = [
        {'nombre': 'Laboratorio A1', 'tipo': 'Cómputo', 'ubicacion': 'Edificio A, PB', 'capacidad': 30, 'apertura': '07:00', 'cierre': '22:00'},
        {'nombre': 'Laboratorio A2', 'tipo': 'Cómputo Avanzado', 'ubicacion': 'Edificio A, N1', 'capacidad': 25, 'apertura': '08:00', 'cierre': '16:00'},
        {'nombre': 'Sala de Juntas 1', 'tipo': 'Reuniones', 'ubicacion': 'Edificio B, N2', 'capacidad': 10, 'apertura': '08:00', 'cierre': '20:00'}
    ]
    return render_template('sitios.html', sitios=sitios_data)

@app.route('/espacios')
def espacios():
    """Detalle técnico de laboratorios (Hardware/Software)"""
    especificaciones = [
        {
            "nombre": "Laboratorio A1",
            "cantidad_pcs": 25,
            "software_base": "VS Code, Laravel, MySQL, Docker",
            "conexion": "Fibra Óptica 1Gbps",
            "disponible": True
        },
        {
            "nombre": "Laboratorio A2",
            "cantidad_pcs": 20,
            "software_base": "Python, FastAPI, PostgreSQL, Conda",
            "conexion": "Ethernet Cat6",
            "disponible": True
        }
    ]
    return render_template('espacios.html', espacios=especificaciones)

# --- EJECUCIÓN ---

if __name__ == '__main__':
    # Puerto 5040 para coincidir con tu Docker Compose y EXPOSE
    app.run(host='0.0.0.0', port=5040, debug=True)