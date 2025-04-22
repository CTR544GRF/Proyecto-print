#!/usr/bin/env python3
import os
import subprocess
import json
from datetime import datetime
import sys
import logging

# Configurar logging para enviar logs a stderr
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    handlers=[
        logging.StreamHandler(sys.stderr)  # Los logs van a stderr
    ]
)

# Configuraci�n de rutas
HOME = os.path.expanduser("~")
BIN_PATH = os.path.join(HOME, "Desktop/Proyecto-print/FDx_SDK_PRO_LINUX_PI_armv7l_3_8_8/bin/pi/sgfplibtest_fdu06")
TEMP_DIR = os.path.join(HOME, "Desktop/huellas_capturadas")

def capture_fingerprint():
    try:
        # Crear directorio temporal si no existe
        os.makedirs(TEMP_DIR, exist_ok=True)
        
        # Cambiar al directorio temporal
        os.chdir(TEMP_DIR)
        
        # Ejecutar el binario SecuGen con timeout
        logging.info("Ejecutando sgfplibtest_fdu06...")
        proceso = subprocess.run(
            [BIN_PATH],
            input="\n\n\nindice_derecho\n\n\n",
            text=True,
            capture_output=True,
            encoding='utf-8',
            errors='replace',
            timeout=30  # 30 segundos de timeout
        )
        
        # Buscar archivo generado
        fingerprint_file = None
        for f in os.listdir(TEMP_DIR):
            if f.endswith(('.min', '.raw')) and 'finger' in f:
                fingerprint_file = os.path.join(TEMP_DIR, f)
                break
        
        if not fingerprint_file:
            error_msg = "No se encontr� archivo de huella generado"
            logging.error(error_msg)
            return {"success": False, "error": error_msg}
        
        # Leer y convertir huella
        with open(fingerprint_file, 'rb') as f:
            fingerprint_data = f.read().hex().upper()
        
        # Renombrar archivo
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        new_filename = f"huella_{timestamp}_{os.path.basename(fingerprint_file)}"
        new_filepath = os.path.join(TEMP_DIR, new_filename)
        os.rename(fingerprint_file, new_filepath)
        
        return {
            "success": True,
            "fingerprint": fingerprint_data,
            "filename": new_filename
        }
        
    except subprocess.TimeoutExpired:
        error_msg = "Tiempo de espera agotado al capturar huella"
        logging.error(error_msg)
        return {"success": False, "error": error_msg}
    except Exception as e:
        error_msg = f"Error inesperado: {str(e)}"
        logging.error(error_msg)
        return {"success": False, "error": error_msg}

if __name__ == "__main__":
    # Limpiar cualquier salida previa
    sys.stdout.flush()
    
    # Ejecutar y devolver solo JSON
    result = capture_fingerprint()
    print(json.dumps(result, ensure_ascii=False))
    
    # Asegurar que no hay m�s salida
    sys.stdout.flush()
    sys.stderr.flush()