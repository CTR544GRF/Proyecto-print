#!/usr/bin/env python3

from pysgfplib import *
from ctypes import *
import os

# Configuración dispositivo SecuGen PRO HUPx
DEVICE_TYPE = SGFDxDeviceName.SG_DEV_HUPx
IMAGE_WIDTH = 300     # Ancho en píxeles
IMAGE_HEIGHT = 400    # Alto en píxeles
TEMPLATE_SIZE = 400   # Tamaño template SG400

def main():
    # Inicializar biblioteca
    sgfplib = PYSGFPLib()
    
    try:
        print("\n=== Inicializando dispositivo SecuGen ===")
        
        # Paso 1: Crear instancia
        result = sgfplib.Create()
        if result != SGFDxErrorCode.SGFDX_ERROR_NONE:
            raise Exception(f"Error al crear instancia: {result}")
        
        # Paso 2: Inicializar dispositivo específico
        result = sgfplib.Init(SGFDxDeviceName.SG_DEV_AUTO)
        if result != SGFDxErrorCode.SGFDX_ERROR_NONE:
            raise Exception(f"Error inicializando dispositivo ({DEVICE_TYPE}): {result}")
            
        # Paso 3: Abrir dispositivo
        result = sgfplib.OpenDevice(0)
        if result != SGFDxErrorCode.SGFDX_ERROR_NONE:
            raise Exception(f"Error abriendo dispositivo: {result}")
        
        # Capturar huella
        print("\n[1] Coloque el dedo en el sensor")
        sgfplib.SetLedOn(True)
        input("[2] Presione Enter para capturar...")
        
        # Buffer para imagen RAW
        raw_image = (c_char * (IMAGE_WIDTH * IMAGE_HEIGHT))()
        
        # Paso 4: Obtener imagen
        result = sgfplib.GetImage(raw_image)
        if result != SGFDxErrorCode.SGFDX_ERROR_NONE:
            raise Exception(f"Error capturando imagen: {result}")
        
        # Buffer para template
        template = (c_char * TEMPLATE_SIZE)()
        
        # Paso 5: Generar template
        result = sgfplib.CreateSG400Template(raw_image, template)
        if result != SGFDxErrorCode.SGFDX_ERROR_NONE:
            raise Exception(f"Error generando template: {result}")
        
        # Guardar archivos
        base_name = input("\n[3] Ingrese nombre para los archivos (ej: user_1): ").strip()
        
        # Guardar imagen RAW
        raw_filename = f"{base_name}.raw"
        with open(raw_filename, "wb") as f:
            f.write(bytes(raw_image))
        
        # Guardar template
        template_filename = f"{base_name}.min"
        with open(template_filename, "wb") as f:
            f.write(bytes(template))
        
        # Verificación
        print("\n=== Archivos generados ===")
        print(f"RAW: {os.path.abspath(raw_filename)}")
        print(f"Tamaño: {os.path.getsize(raw_filename)} bytes")
        print(f"\nMIN: {os.path.abspath(template_filename)}")
        print(f"Tamaño: {os.path.getsize(template_filename)} bytes")
        
    except Exception as e:
        print(f"\n[ERROR] {str(e)}")
    finally:
        # Limpieza
        sgfplib.SetLedOn(False)
        sgfplib.CloseDevice()
        sgfplib.Terminate()
        print("\n[+] Dispositivo liberado correctamente")

if __name__ == '__main__':
    main()
