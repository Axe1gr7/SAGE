from pydantic import BaseModel
from datetime import datetime
from app.models.SAGE_BD import TipoReserva, EstadoReserva

class ReservaBase(BaseModel):
    id_espacio: int
    id_equipo: int | None = None
    tipo_reserva: TipoReserva
    fecha_hora_inicio: datetime
    fecha_hora_fin: datetime
    observaciones: str | None = None

class ReservaCreate(ReservaBase):
    id_estudiante_beneficiario: int | None = None
    id_clase_beneficiario: int | None = None
    id_evento_beneficiario: int | None = None

class ReservaUpdate(BaseModel):
    fecha_hora_inicio: datetime | None = None
    fecha_hora_fin: datetime | None = None
    observaciones: str | None = None
    estado: EstadoReserva | None = None
    motivo_cancelacion: str | None = None

class ReservaResponse(ReservaBase):
    id_reserva: int
    estado: EstadoReserva
    motivo_cancelacion: str | None = None
    id_estudiante: int | None = None
    id_clase: int | None = None
    id_evento: int | None = None
    id_administrador_creador: int | None = None
    id_estudiante_creador: int | None = None
    estatus: int
    fecha_creacion: datetime
    fecha_actualizacion: datetime

    class Config:
        from_attributes = True