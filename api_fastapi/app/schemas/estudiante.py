from pydantic import BaseModel, EmailStr
from datetime import datetime
from app.models.SAGE_BD import CarreraEnum

class EstudianteBase(BaseModel):
    nombre_completo: str
    matricula: str
    correo: EmailStr
    carrera: CarreraEnum

class EstudianteCreate(EstudianteBase):
    contrasena: str

class EstudianteUpdate(BaseModel):
    nombre_completo: str | None = None
    matricula: str | None = None
    correo: EmailStr | None = None
    carrera: CarreraEnum | None = None
    contrasena: str | None = None

class EstudianteResponse(EstudianteBase):
    id_estudiante: int
    estatus: int
    fecha_creacion: datetime
    fecha_actualizacion: datetime

    class Config:
        from_attributes = True