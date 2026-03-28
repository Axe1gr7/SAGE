from pydantic import BaseModel
from datetime import datetime

class ClaseBase(BaseModel):
    nombre: str
    materia: str
    grupo: str
    docente: str
    correo_docente: str | None = None
    horario: str | None = None
    id_administrador: int | None = None
    id_espacio_asignado: int | None = None

class ClaseCreate(ClaseBase):
    pass

class ClaseUpdate(BaseModel):
    nombre: str | None = None
    materia: str | None = None
    grupo: str | None = None
    docente: str | None = None
    correo_docente: str | None = None
    horario: str | None = None
    id_administrador: int | None = None
    id_espacio_asignado: int | None = None

class ClaseResponse(ClaseBase):
    id_clase: int
    estatus: int
    fecha_creacion: datetime
    fecha_actualizacion: datetime

    class Config:
        from_attributes = True