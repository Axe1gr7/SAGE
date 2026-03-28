from pydantic import BaseModel
from datetime import datetime
from app.models.SAGE_BD import EstadoOperativo

class EquipoBase(BaseModel):
    id_espacio: int
    nombre_equipo: str
    tipo_equipo: str
    estado_operativo: EstadoOperativo = EstadoOperativo.OPERATIVO

class EquipoCreate(EquipoBase):
    pass

class EquipoUpdate(BaseModel):
    nombre_equipo: str | None = None
    tipo_equipo: str | None = None
    estado_operativo: EstadoOperativo | None = None

class EquipoResponse(EquipoBase):
    id_equipo: int
    estatus: int
    fecha_creacion: datetime
    fecha_actualizacion: datetime

    class Config:
        from_attributes = True