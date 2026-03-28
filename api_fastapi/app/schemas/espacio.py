from pydantic import BaseModel
from datetime import datetime, time

class EspacioBase(BaseModel):
    tipo_espacio: str
    nombre: str
    ubicacion: str
    capacidad: int
    horario_apertura: time
    horario_cierre: time
    disponible: bool = True

class EspacioCreate(EspacioBase):
    pass

class EspacioUpdate(BaseModel):
    tipo_espacio: str | None = None
    nombre: str | None = None
    ubicacion: str | None = None
    capacidad: int | None = None
    horario_apertura: time | None = None
    horario_cierre: time | None = None
    disponible: bool | None = None

class EspacioResponse(EspacioBase):
    id_espacio: int
    estatus: int
    fecha_creacion: datetime
    fecha_actualizacion: datetime

    class Config:
        from_attributes = True