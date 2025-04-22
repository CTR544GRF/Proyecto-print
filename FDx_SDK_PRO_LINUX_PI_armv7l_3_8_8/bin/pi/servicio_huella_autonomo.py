#!/usr/bin/env python3
import RPi.GPIO as GPIO
import time
import subprocess
import os
from sqlalchemy import create_engine, text
from pathlib import Path

# ---------------------------
# CONFIGURACIÓN PERSONALIZADA
# ---------------------------

# Configuración GPIO
BUTTON_PIN = 12  # GPIO12 (pin físico 32)

# Configuración SecuGen
SG_BIN_PATH = "/home/Usuario/Desktop/Proyecto-print/FDx_SDK_PRO_LINUX_PI_armv7l_3_8_8/bin/pi/sgfplibtest_fdu06"
CAPTURES_DIR = "/home/Usuario/Desktop/huellas_capturadas"

# Configuración BD Laravel
DB_ENGINE = create_engine("mysql+pymysql://root:root@localhost/proyecto_print")

# ---------------------------
# FUNCIONES PRINCIPALES
# ---------------------------

def capturar_huella():
    """Captura huella y devuelve data binaria"""
    try:
        # Crear directorio si no existe
        os.makedirs(CAPTURES_DIR, exist_ok=True)
        
        # Ejecutar binario SecuGen
        proceso = subprocess.run(
            [SG_BIN_PATH],
            cwd=CAPTURES_DIR,  # Cambia al directorio de capturas
            input="\n\n\nindice_derecho\n\n\n",
            text=True,
            capture_output=True,
            timeout=30
        )
        
        # Buscar archivo de huella más reciente
        archivos = sorted(
            [f for f in os.listdir(CAPTURES_DIR) if f.endswith(('.raw', '.min'))],
            key=lambda x: os.path.getmtime(os.path.join(CAPTURES_DIR, x)),
            reverse=True
        )
        
        if archivos:
            with open(os.path.join(CAPTURES_DIR, archivos[0]), 'rb') as f:
                return f.read()
        return None

    except Exception as e:
        print(f"[ERROR] Fallo en captura: {str(e)}")
        return None

def normalizar_huella(huella_bin):
    """Normaliza formato de huella para comparación"""
    return huella_bin.hex().upper().replace(" ", "").strip()

def buscar_en_bd(huella_normalizada):
    """Busca coincidencias en la BD con tolerancia"""
    try:
        with DB_ENGINE.connect() as conn:
            # Busca coincidencias parciales (primeros 40 caracteres)
            query = text("""
                SELECT nombre, numero_documento, email 
                FROM users 
                WHERE fingerprint_data LIKE :patron
                OR fingerprint_data LIKE :patron2
                LIMIT 1
            """)
            
            # Patrones de búsqueda (ajustar según necesidad)
            patron = f"%{huella_normalizada[:40]}%"
            patron2 = f"%{huella_normalizada[20:60]}%"
            
            resultado = conn.execute(query, {
                "patron": patron,
                "patron2": patron2
            })
            
            return resultado.fetchone()
            
    except Exception as e:
        print(f"[ERROR] Consulta BD: {str(e)}")
        return None

# ---------------------------
# PROGRAMA PRINCIPAL
# ---------------------------

def main():
    # Configuración GPIO
    GPIO.setmode(GPIO.BCM)
    GPIO.setup(BUTTON_PIN, GPIO.IN, pull_up_down=GPIO.PUD_UP)
    
    print("\n" + "="*50)
    print("Sistema de Identificación por Huella Digital")
    print("="*50 + "\n")
    print("[ESTADO] Esperando pulsación del botón...")

    try:
        while True:
            if GPIO.input(BUTTON_PIN) == GPIO.LOW:
                print("\n[ACCION] Capturando huella...")
                
                # 1. Capturar huella
                huella_bin = capturar_huella()
                
                if huella_bin:
                    # 2. Normalizar y comparar
                    huella_norm = normalizar_huella(huella_bin)
                    usuario = buscar_en_bd(huella_norm)
                    
                    if usuario:
                        print("\n[RESULTADO] ¡COINCIDENCIA ENCONTRADA!")
                        print(f" • Nombre: {usuario.nombre}")
                        print(f" • Documento: {usuario.numero_documento}")
                        print(f" • Email: {usuario.email}")
                    else:
                        print("\n[RESULTADO] No se encontraron coincidencias")
                        
                    # Opcional: Guardar huella para depuración
                    debug_path = os.path.join(CAPTURES_DIR, "ultima_captura.hex")
                    with open(debug_path, 'w') as f:
                        f.write(huella_norm)
                    print(f"[DEBUG] Huella guardada en: {debug_path}")
                else:
                    print("\n[ERROR] No se pudo capturar la huella")
                
                # Espera para evitar rebotes
                time.sleep(2)
                print("\n[ESTADO] Esperando nueva pulsación...")
                
            time.sleep(0.1)
            
    except KeyboardInterrupt:
        print("\nDeteniendo sistema...")
    finally:
        GPIO.cleanup()

if __name__ == "__main__":
    main()
