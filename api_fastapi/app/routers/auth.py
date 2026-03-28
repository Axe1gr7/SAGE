from fastapi import APIRouter, Depends, HTTPException, status
from fastapi.security import OAuth2PasswordRequestForm
from sqlalchemy.orm import Session
from app.data.database import get_db
from app.models.SAGE_BD import Estudiante, Administrador
from app.schemas.token import Token
from app.schemas.estudiante import EstudianteCreate, EstudianteResponse
from app.auth import authenticate_user, create_access_token, get_password_hash

router = APIRouter(prefix="/auth", tags=["Autenticación"])

@router.post("/login", response_model=Token)
async def login(form_data: OAuth2PasswordRequestForm = Depends(), db: Session = Depends(get_db)):
    
    # 1. Intentamos buscarlo primero como estudiante
    role = "estudiante"
    user = authenticate_user(db, form_data.username, form_data.password, role)
    
    # 2. Si no lo encontramos como estudiante, intentamos como admin
    if not user:
        role = "admin"
        user = authenticate_user(db, form_data.username, form_data.password, role)
        
    # 3. Si de plano no existe en ninguna de las dos tablas (o la contraseña está mal)
    if not user:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Correo o contraseña incorrectos",
            headers={"WWW-Authenticate": "Bearer"},
        )
        
    # 4. Generamos el token usando el ID correcto según su rol
    access_token = create_access_token(
        data={
            "sub": str(user.id_administrador) if role == "admin" else str(user.id_estudiante),
            "role": role
        }
    )
    return {"access_token": access_token, "token_type": "bearer"}

@router.post("/registro", response_model=EstudianteResponse)
async def registrar_estudiante(estudiante: EstudianteCreate, db: Session = Depends(get_db)):
    if db.query(Estudiante).filter(Estudiante.matricula == estudiante.matricula).first():
        raise HTTPException(status_code=400, detail="Matrícula ya registrada")
    if db.query(Estudiante).filter(Estudiante.correo == estudiante.correo).first():
        raise HTTPException(status_code=400, detail="Correo ya registrado")
        
    hashed = get_password_hash(estudiante.contrasena)
    db_est = Estudiante(
        nombre_completo=estudiante.nombre_completo,
        matricula=estudiante.matricula,
        correo=estudiante.correo,
        contrasena=hashed,
        carrera=estudiante.carrera
    )
    db.add(db_est)
    db.commit()
    db.refresh(db_est)
    return db_est