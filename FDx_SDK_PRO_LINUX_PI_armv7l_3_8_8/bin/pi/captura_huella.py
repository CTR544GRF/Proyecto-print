#!/usr/bin/env python3
import os
import subprocess
import json
from datetime import datetime

# Configuración de rutas
HOME = os.path.expanduser("~")
BIN_PATH = os.path.join(HOME, "Desktop/Proyecto-print/FDx_SDK_PRO_LINUX_PI_armv7l_3_8_8/bin/pi/sgfplibtest_fdu06")
TEMP_DIR = os.path.join(HOME, "Desktop/huellas_capturadas")

def capture_fingerprint():
    print("🟢 Iniciando proceso de captura de huella...")
    
    # Crear directorio temporal si no existe
    os.makedirs(TEMP_DIR, exist_ok=True)
    
    try:
        # Ejecutar el binario SecuGen
        print("🔵 Ejecutando sgfplibtest_fdu06...")
        proceso = subprocess.run(
            [BIN_PATH],
            input="\n\n\nindice_derecho\n\n\n",  # Simula las pulsaciones de tecla necesarias
            text=True,
            capture_output=True
        )
        
        if proceso.returncode != 0:
            error_msg = f"Error en la captura: {proceso.stderr}"
            print(f"🔴 {error_msg}")
            return {"success": False, "error": error_msg}
        
        print("🟢 Captura completada exitosamente")
        
        # Buscar archivos generados (asumiendo que generará .min o .raw)
        fingerprint_file = None
        for f in os.listdir():
            if f.endswith(('.min', '.raw')):
                fingerprint_file = f
                break
        
        if not fingerprint_file:
            error_msg = "No se encontró archivo de huella generado"
            print(f"🔴 {error_msg}")
            return {"success": False, "error": error_msg}
        
        # Leer el archivo de huella en modo binario y convertirlo a hexadecimal
        with open(fingerprint_file, 'rb') as f:
            fingerprint_data = f.read().hex().upper()
        
        # Mover el archivo al directorio temporal
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        new_filename = f"huella_{timestamp}_{fingerprint_file}"
        os.rename(fingerprint_file, os.path.join(TEMP_DIR, new_filename))
        
        print("✅ Huella capturada y convertida a hexadecimal")
        return {
            "success": True,
            "fingerprint": fingerprint_data,
            "filename": new_filename
        }
        
    except Exception as e:
        error_msg = f"Error inesperado: {str(e)}"
        print(f"🔴 {error_msg}")
        return {"success": False, "error": error_msg}

if __name__ == "__main__":
    # Cuando se ejecuta directamente, imprimir el resultado como JSON
    result = capture_fingerprint()
    print(json.dumps(result))