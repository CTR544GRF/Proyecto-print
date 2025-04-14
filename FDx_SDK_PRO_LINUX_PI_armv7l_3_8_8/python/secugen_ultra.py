#!/usr/bin/env python3
import ctypes
import time
from pysgfplib import *

def main():
    print("🚀 INICIO DEL SISTEMA SECUGEN PRO HUPx 🚀")
    
    try:
        # 1. Carga manual de biblioteca
        lib = ctypes.CDLL('/usr/local/lib/libsgfplib.so', mode=ctypes.RTLD_GLOBAL)
        
        # 2. Inicialización
        sgfplib = PYSGFPLib()
        print("✅ Biblioteca cargada correctamente")
        
        # 3. Creación de instancia
        if sgfplib.Create() != 0:
            raise RuntimeError("Error en Create()")
        
        # 4. Apertura especial del dispositivo
        print("🔌 Conectando al dispositivo...")
        if sgfplib.OpenDevice(1) != 0:  # Usando puerto alternativo
            raise RuntimeError("Error en OpenDevice()")
        
        # 5. Inicialización en modo Hamster Pro 20
        if sgfplib.Init(4) != 0:
            raise RuntimeError("Error en Init()")
        
        # 6. Prueba física del LED
        print("\n💡 PRUEBA DEL LED - Debe encenderse ahora")
        sgfplib.SetLedOn(True)
        time.sleep(3)
        sgfplib.SetLedOn(False)
        
        # 7. Captura de huella
        print("\n🖐️ LISTO PARA CAPTURA - Coloque su dedo")
        width, height = 300, 400
        img_buffer = (ctypes.c_char * width * height)()
        
        if sgfplib.GetImage(img_buffer) == 0:
            with open('huella.raw', 'wb') as f:
                f.write(img_buffer)
            print("🎉 ¡CAPTURA EXITOSA! Verifique huella.raw")
        else:
            print("❌ Fallo en captura - Revise conexión USB")
            
    except Exception as e:
        print(f"💥 ERROR: {str(e)}")
    finally:
        try:
            sgfplib.Terminate()
        except:
            pass

if __name__ == "__main__":
    main()
