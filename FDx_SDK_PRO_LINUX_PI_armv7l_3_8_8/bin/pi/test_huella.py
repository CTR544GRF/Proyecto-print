#!/usr/bin/env python3

import json 
import os
import sys
import pexpect
import logging
from datetime import datetime

# Configuración (ajusta estas rutas)
BIN_PATH = "/home/Usuario/Desktop/Proyecto-print/FDx_SDK_PRO_LINUX_PI_armv7l_3_8_8/bin/pi/sgfplibtest_fdu06"
TEMP_DIR = "/home/Usuario/Desktop/huellas_capturadas"
LOG_FILE = "/tmp/huella_debug.log"

# Configurar logging
logging.basicConfig(
    filename=LOG_FILE,
    level=logging.DEBUG,
    format='%(asctime)s - %(levelname)s - %(message)s'
)

def setup_environment():
    """Verifica y prepara el entorno de ejecución"""
    try:
        if not os.path.exists(TEMP_DIR):
            os.makedirs(TEMP_DIR, mode=0o777, exist_ok=True)
            logging.info(f"Directorio creado: {TEMP_DIR}")
            
        if not os.access(BIN_PATH, os.X_OK):
            raise PermissionError(f"Sin permisos de ejecución en {BIN_PATH}")
            
        return True
    except Exception as e:
        logging.error(f"Error en setup: {str(e)}")
        return False

def capture_fingerprint(finger_name="indice_derecho"):
    """Captura una huella usando el binario del sensor"""
    try:
        if not setup_environment():
            return {"success": False, "error": "Error en configuración inicial"}
            
        logging.info("Iniciando captura...")
        child = pexpect.spawn(BIN_PATH, timeout=20)
        
        # Flujo interactivo
        child.expect("Which finger would you like to test with?.*>>")
        child.sendline(finger_name)
        
        child.expect("Capture 1. Please place.*press <ENTER>")
        child.sendline("")  # Primer captura
        
        child.expect("Capture 2. Remove and replace.*press <ENTER>")
        child.sendline("")  # Segunda captura
        
        # Esperar finalización
        child.expect(pexpect.EOF, timeout=30)
        child.close()
        
        if child.exitstatus != 0:
            raise RuntimeError(f"Código de salida: {child.exitstatus}")
            
        # Buscar archivo generado
        fingerprint_file = None
        for f in os.listdir("."):
            if f.endswith(('.min', '.raw')):
                fingerprint_file = f
                break
                
        if not fingerprint_file:
            raise FileNotFoundError("No se encontró archivo de huella")
            
        # Mover y convertir
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        new_filename = f"huella_{timestamp}_{fingerprint_file}"
        os.rename(fingerprint_file, os.path.join(TEMP_DIR, new_filename))
        
        with open(os.path.join(TEMP_DIR, new_filename), 'rb') as f:
            fingerprint_data = f.read().hex().upper()
            
        return {
            "success": True,
            "fingerprint": fingerprint_data,
            "filename": new_filename
        }
        
    except pexpect.TIMEOUT:
        error_msg = "Tiempo de espera agotado"
        logging.error(error_msg)
        return {"success": False, "error": error_msg}
    except pexpect.ExceptionPexpect as e:
        error_msg = f"Error en comunicación: {str(e)}"
        logging.error(error_msg)
        return {"success": False, "error": error_msg}
    except Exception as e:
        error_msg = f"Error inesperado: {str(e)}"
        logging.error(error_msg)
        return {"success": False, "error": error_msg}

if __name__ == "__main__":
    # Modo prueba directa
    print("=== Modo Prueba ===")
    result = capture_fingerprint()
    
    print("\nResultado:")
    print(json.dumps(result, indent=2))
    
    if result['success']:
        print(f"\nDebug: Log guardado en {LOG_FILE}")
    else:
        print(f"\nError: {result['error']}\nVer log: {LOG_FILE}")
