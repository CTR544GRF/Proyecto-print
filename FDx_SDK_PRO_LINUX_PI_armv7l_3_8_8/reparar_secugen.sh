#!/bin/bash
echo "🛠️  Reparación Completa del Sistema SecuGen"

# 1. Detener servicios conflictivos
sudo systemctl stop brltty 2>/dev/null

# 2. Configurar permisos USB
echo 'SUBSYSTEM=="usb", ATTR{idVendor}=="1162", MODE="0666"' | sudo tee /etc/udev/rules.d/99-secugen.rules
sudo udevadm control --reload

# 3. Forzar reconexión USB
sudo bash -c "
for dev in $(ls /sys/bus/usb/devices/*/idVendor | xargs grep -l 1162); do
    echo 0 > ${dev%/idVendor}/authorized
    sleep 2
    echo 1 > ${dev%/idVendor}/authorized
    sleep 3
done
"

# 4. Instalar dependencias críticas
sudo apt-get install -y libusb-1.0-0 libusb-1.0-0-dev python3-usb

echo "✅ Reparación completada. Reinicie el sistema."
