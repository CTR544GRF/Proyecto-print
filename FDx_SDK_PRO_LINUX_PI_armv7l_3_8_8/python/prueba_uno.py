#!/usr/bin/env python3
import ctypes
from pysgfplib import *

def main():
    print("🔍 Prueba Directa SecuGen PRO HUPx")
    
    try:
        # 1. Inicialización
        sgfplib = PYSGFPLib()
        print("✅ Biblioteca cargada")
        
        # 2. Crear instancia
        if sgfplib.Create() != 0:
            print("❌ Error en Create()")
            return
        
        # 3. Abrir dispositivo
        print("🔌 Intentando abrir dispositivo...")
        if sgfplib.OpenDevice(0) != 0:
            print("⚠️ Error en OpenDevice() - Probando modo alternativo")
            sgfplib.CloseDevice()
            if sgfplib.OpenDevice(1) != 0:
                print("❌ No se pudo abrir el dispositivo")
                return
        
        # 4. Inicializar
        print("⚙️ Inicializando...")
        if sgfplib.Init(4) != 0:
            print("⚠️ Error en Init() - Probando modo AUTO")
            if sgfplib.Init(0) != 0:
                print("❌ No se pudo inicializar")
                return
        
        # 5. Control LED
        print("💡 Probando LED...")
        sgfplib.SetLedOn(True)
        input("¿El LED está encendido? Presione Enter...")
        sgfplib.SetLedOn(False)
        
        print("✨ Prueba completada")
        
    except Exception as e:
        print(f"💥 Error crítico: {str(e)}")
    finally:
        sgfplib.Terminate()

if __name__ == "__main__":
    main()
