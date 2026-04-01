from app.data.database import SessionLocal
from app.models.SAGE_BD import Estudiante, Administrador, EstatusTinyInt
from app.auth import get_password_hash

db = SessionLocal()
try:
    print("Iniciando reparación de usuarios...")
    # Generamos el hash que tu sistema reconoce
    password_fix = get_password_hash("1234")
    
    # 1. Reparar Administrador
    admin = db.query(Administrador).filter(Administrador.correo == "axelgr@upq.mx").first()
    if admin:
        admin.contrasena = password_fix
        admin.estatus = EstatusTinyInt.ACTIVO # Asegura que sea 0
        print(f"✅ Admin {admin.correo} actualizado.")
        
    # 2. Reparar Estudiantes
    estudiantes = db.query(Estudiante).all()
    for est in estudiantes:
        est.contrasena = password_fix
        est.estatus = EstatusTinyInt.ACTIVO # Asegura que sea 0
        print(f"✅ Estudiante {est.correo} actualizado.")
        
    db.commit()
    print("\n🎉 Proceso terminado. Intenta entrar con la clave '1234'.")
except Exception as e:
    db.rollback()
    print(f"❌ Error: {e}")
finally:
    db.close()
    