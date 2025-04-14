#!/usr/bin/env python3
import ctypes
import time
from pysgfplib import *

def main():
    print("🔥 PRUEBA DEFINITIVA SECUGEN PRO HUPx 🔥")
    
    try:
        # 1. Cargar biblioteca manualmente
        lib = ctypes.CDLL('/usr/local/lib/libsgfplib.so', mode=ctypes.RTLD_GLOBAL)
        
        # 2. Inicializar
        sgfplib = PYSGFPLib()
        print("✅ Biblioteca cargada")
        
        # 3. Crear instancia con verificación
        if sgfplib.Create() != 0:
            raise RuntimeError("Error en Create()")
        
        # 4. Abrir dispositivo con múltiples intentos
        for port in [0, 1]:
            if sgfplib.OpenDevice(port) == 0:
                print(f"✅ Dispositivo abierto en puerto {port}")
                break
        else:
            raise RuntimeError("No se pudo abrir el dispositivo")
        
        # 5. Inicialización con modo alternativo
        for mode in [4, 0, 5]:  # Pro20, Auto, Plus
            if sgfplib.Init(mode) == 0:
                print(f"✅ Inicializado en modo {mode}")
                break
        else:
            raise RuntimeError("No se pudo inicializar")
        
        # 6. Prueba física del LED
        print("\n💡 TEST FÍSICO DEL LED (debe encenderse)")
        sgfplib.SetLedOn(True)
        time.sleep(3)
        sgfplib.SetLedOn(False)
        
        # 7. Captura de huella
        print("\n🖐️ COLOCA TU DEDO EN EL SENSOR")
        width, height = 300, 400
        img_buffer = (ctypes.c_char * width * height)()
        
        for attempt in range(3):
            if sgfplib.GetImage(img_buffer) == 0:
                with open('huella.raw', 'wb') as f:
                    f.write(img_buffer)
                print("🎉 ¡HUELLA CAPTURADA CORRECTAMENTE!")
                return
            print(f"Intento {attempt+1} fallido")
            time.sleep(1)
            
        print("❌ No se pudo capturar la huella")
        
    except Exception as e:
        print(f"💣 ERROR CRÍTICO: {str(e)}")
    finally:
        try:
            sgfplib.Terminate()
        except:
            pass

if __name__ == "__main__":
    main()
