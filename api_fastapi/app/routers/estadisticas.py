from fastapi import APIRouter, Depends, Query
from sqlalchemy import func, extract
from sqlalchemy.orm import Session
from app.data.database import get_db
from app.models.SAGE_BD import Reserva, Espacio
from app.auth import get_current_admin
from datetime import datetime

router = APIRouter(prefix="/estadisticas", tags=["Estadísticas"])

@router.get("/espacios-mas-reservados")
async def espacios_mas_reservados(
    anio: int = Query(default=datetime.now().year),
    mes: int = Query(default=datetime.now().month),
    db: Session = Depends(get_db),
    admin = Depends(get_current_admin)
):
    resultados = db.query(
        Espacio.id_espacio,
        Espacio.nombre,
        func.count(Reserva.id_reserva).label("total")
    ).join(Reserva, Espacio.id_espacio == Reserva.id_espacio).filter(
        Reserva.estado == "activa",
        Reserva.estatus == 0,
        extract('year', Reserva.fecha_hora_inicio) == anio,
        extract('month', Reserva.fecha_hora_inicio) == mes
    ).group_by(Espacio.id_espacio).order_by(
        func.count(Reserva.id_reserva).desc()
    ).limit(5).all()
    return [{"id": r[0], "nombre": r[1], "total": r[2]} for r in resultados]

@router.get("/horarios-demanda")
async def horarios_demanda(
    db: Session = Depends(get_db),
    admin = Depends(get_current_admin)
):
    results = db.query(
        func.extract('hour', Reserva.fecha_hora_inicio).label("hora"),
        func.count(Reserva.id_reserva).label("total")
    ).filter(
        Reserva.estado == "activa",
        Reserva.estatus == 0
    ).group_by("hora").order_by("hora").all()
    return [{"hora": int(r[0]), "total": r[1]} for r in results]

@router.get("/ocupacion-espacio")
async def ocupacion_espacio(
    espacio_id: int,
    fecha: datetime = Query(default=datetime.now().date()),
    db: Session = Depends(get_db),
    admin = Depends(get_current_admin)
):
    reservas = db.query(Reserva).filter(
        Reserva.id_espacio == espacio_id,
        Reserva.estatus == 0,
        Reserva.estado == "activa",
        func.date(Reserva.fecha_hora_inicio) == fecha.date()
    ).order_by(Reserva.fecha_hora_inicio).all()
    return [
        {
            "inicio": r.fecha_hora_inicio,
            "fin": r.fecha_hora_fin,
            "tipo": r.tipo_reserva.value,
            "beneficiario": r.id_estudiante or r.id_clase or r.id_evento
        }
        for r in reservas
    ]