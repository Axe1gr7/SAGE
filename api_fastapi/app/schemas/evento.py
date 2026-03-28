from pydantic import BaseModel
from datetime import datetime

class EventoBase(BaseModel):
    nombre: str
    descripcion: str | None = None
    fecha_inicio: datetime | None = None
    fecha_fin: datetime | None = None

class EventoCreate(EventoBase):
    pass

class EventoUpdate(BaseModel):
    nombre: str | None = None
    descripcion: str | None = None
    fecha_inicio: datetime | None = None
    fecha_fin: datetime | None = None

class EventoResponse(EventoBase):
    id_evento: int
    estatus: int
    fecha_creacion: datetime
    fecha_actualizacion: datetime

    class Config:
        from_attributes = True