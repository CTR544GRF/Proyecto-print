#!/usr/bin/env python3
import os
import shutil
import subprocess
from datetime import datetime

# Configuración de rutas
HOME = os.path.expanduser("~")
BIN_PATH = os.path.join(HOME, "Desktop/Proyecto-print/FDx_SDK_PRO_LINUX_PI_armv7l_3_8_8/bin/pi/sgfplibtest_fdu06")
DESTINO = os.path.join(HOME, "Desktop/huellas_capturadas")

def main():
    print("🟢 Iniciando proceso de captura de huella...")
    
    # Crear directorio de destino si no existe
    os.makedirs(DESTINO, exist_ok=True)
    
    try:
        # Ejecutar el binario SecuGen
        print("🔵 Ejecutando sgfplibtest_fdu06...")
        proceso = subprocess.run(
            [BIN_PATH],
            input="\n\n\nindice_derecho\n\n\n",  # Simula las pulsaciones de tecla necesarias
            text=True,
            capture_output=True
        )
        
        # Verificar si la ejecución fue exitosa
        if proceso.returncode != 0:
            print(f"🔴 Error en la captura:\n{proceso.stderr}")
            return
        
        print("🟢 Captura completada exitosamente")
        
        # Buscar y mover archivos generados
        print("🔵 Buscando archivos de huella...")
        archivos_huella = [
            f for f in os.listdir() 
            if f.startswith("indice_derecho") or f.endswith(('.raw', '.min'))
        ]
        
        if not archivos_huella:
            print("⚠️ No se encontraron archivos de huella")
            return
        
        # Mover cada archivo al escritorio
        for archivo in archivos_huella:
            timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
            nuevo_nombre = f"huella_{timestamp}_{archivo}"
            destino = os.path.join(DESTINO, nuevo_nombre)
            
            shutil.move(archivo, destino)
            print(f"📄 Archivo movido: {destino}")
            
        print(f"\n✅ Proceso completado! Archivos guardados en:\n{DESTINO}")
        
    except Exception as e:
        print(f"🔴 Error inesperado: {str(e)}")

if __name__ == "__main__":
    main()
