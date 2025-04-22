#!/usr/bin/env python3
import os
import shutil
import subprocess
from datetime import datetime
import binascii
import base64

# Configuración de rutas
HOME = os.path.expanduser("~")
BIN_PATH = os.path.join(HOME, "Desktop/Proyecto-print/FDx_SDK_PRO_LINUX_PI_armv7l_3_8_8/bin/pi/sgfplibtest_fdu06")
DESTINO = os.path.join(HOME, "Desktop/huellas_capturadas")

def file_to_hex(filepath):
    """Convierte un archivo a representación hexadecimal"""
    with open(filepath, 'rb') as f:
        content = f.read()
    return binascii.hexlify(content).decode('utf-8')

def file_to_base64(filepath):
    """Convierte un archivo a Base64"""
    with open(filepath, 'rb') as f:
        content = f.read()
    return base64.b64encode(content).decode('utf-8')

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
        
        # Buscar archivos generados
        print("🔵 Buscando archivos de huella...")
        archivos_huella = [
            f for f in os.listdir() 
            if f.startswith("indice_derecho") or f.endswith(('.raw', '.min'))
        ]
        
        if not archivos_huella:
            print("⚠️ No se encontraron archivos de huella")
            return
        
        resultados = []
        
        # Procesar cada archivo
        for archivo in archivos_huella:
            timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
            nuevo_nombre = f"huella_{timestamp}_{archivo}"
            destino = os.path.join(DESTINO, nuevo_nombre)
            
            # Mover el archivo
            shutil.move(archivo, destino)
            
            # Obtener representaciones del archivo
            hex_data = file_to_hex(destino)
            base64_data = file_to_base64(destino)
            file_size = os.path.getsize(destino)
            
            # Guardar resultados
            resultados.append({
                'nombre_archivo': nuevo_nombre,
                'ruta': destino,
                'tamaño_bytes': file_size,
                'hexadecimal': hex_data[:100] + '...' if len(hex_data) > 100 else hex_data,  # Muestra solo el inicio
                'base64': base64_data[:100] + '...' if len(base64_data) > 100 else base64_data
            })
            
            print(f"📄 Archivo movido y convertido: {destino}")
        
        # Mostrar resumen
        print("\n✅ Proceso completado! Resultados:")
        for resultado in resultados:
            print(f"\nArchivo: {resultado['nombre_archivo']}")
            print(f"Tamaño: {resultado['tamaño_bytes']} bytes")
            print(f"Hex (inicio): {resultado['hexadecimal']}")
            print(f"Base64 (inicio): {resultado['base64']}")
        
        print(f"\nArchivos guardados en: {DESTINO}")
        
        # Devolver los resultados (podrías usar return resultados si necesitas procesarlos)
        return resultados
        
    except Exception as e:
        print(f"🔴 Error inesperado: {str(e)}")
        return None

if __name__ == "__main__":
    main()
