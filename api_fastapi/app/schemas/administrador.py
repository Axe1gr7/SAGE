from pydantic import BaseModel, EmailStr
from datetime import datetime

class AdministradorBase(BaseModel):
    nombre_completo: str
    puesto: str | None = None
    correo: EmailStr

class AdministradorCreate(AdministradorBase):
    contrasena: str

class AdministradorUpdate(BaseModel):
    nombre_completo: str | None = None
    puesto: str | None = None
    correo: EmailStr | None = None
    contrasena: str | None = None

class AdministradorResponse(AdministradorBase):
    id_administrador: int
    estatus: int
    fecha_creacion: datetime
    fecha_actualizacion: datetime

    class Config:
        from_attributes = True