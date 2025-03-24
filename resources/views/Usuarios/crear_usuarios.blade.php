@extends('home_p')

@section('style')
{{ asset('css/user_create.css') }}
@endsection


@section('panel') Crear Usuario @endsection
@section('descripcion') Completa el formulario para registrar un nuevo usuario y capturar su huella dactilar. @endsection

@section('principal_content')

<form action="{{ route('usuarios.store') }}" method="POST" enctype="multipart/form-data">
    @csrf 
    <div class="form-group">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" required>
    </div>

    <div class="form-group">
        <label for="numero_documento">Número de Documento</label>
        <input type="text" id="numero_documento" name="numero_documento" required>
    </div>

    <div class="form-group">
        <label for="email">Correo Electrónico</label>
        <input type="email" id="email" name="email" required>
    </div>

    <div class="form-group">
        <label for="numero_telefono">Número de Teléfono</label>
        <input type="text" id="numero_telefono" name="numero_telefono" required>
    </div>

    <div class="form-group">
        <label for="direccion_residencia">Dirección de Residencia</label>
        <input type="text" id="direccion_residencia" name="direccion_residencia" required>
    </div>

    <div class="form-group">
        <label for="tipo_usuario">Tipo de Usuario</label>
        <select id="tipo_usuario" name="tipo_usuario" required>
            <option value="" disabled selected>Seleccione un tipo</option>
            <option value="administrador">Administrador</option>
            <option value="usuario">Usuario</option>
        </select>
    </div>

    <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required>
    </div>

    <!-- Sección para captura de huella -->
    <div class="form-group" id="huella" >
        <label for="fingerprint">Huella Dactilar</label>
        <button type="button" id="scanFingerprint" disabled>Capturar Huella</button>
        <input type="hidden" id="fingerprintData" name="fingerprint_data">
        <div id="fingerprintPreview"></div>
        <div id="sensorStatus"></div>
    </div>

    <button type="submit">Registrar Usuario</button>
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Verificar disponibilidad del sensor al cargar la página
    fetch("{{ route('fingerprint.check') }}")
    .then(response => response.json())
    .then(data => {
        const sensorStatus = document.getElementById('sensorStatus');
        if(data.sensorAvailable) {
            sensorStatus.innerHTML = '<p style="color: green;">Sensor de huella detectado.</p>';
            document.getElementById('scanFingerprint').disabled = false;
        } else {
            sensorStatus.innerHTML = '<p style="color: red;">No se detectó sensor de huella. Conéctelo para proceder.</p>';
        }
    })
    .catch(error => {
        console.error('Error verificando sensor:', error);
    });

    // Evento para capturar la huella
    document.getElementById('scanFingerprint').addEventListener('click', function() {
        Swal.fire({
            title: 'Escaneando huella...',
            text: 'Coloque su dedo en el sensor.',
            didOpen: () => {
                fetch("{{ route('fingerprint.capture') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success){
                        document.getElementById('fingerprintData').value = data.fingerprintData;
                        document.getElementById('fingerprintPreview').innerHTML = 
                            '<p style="color: green;">Huella capturada correctamente.</p>';
                        Swal.close();
                    } else {
                        Swal.fire('Error', 'No se pudo capturar la huella', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error al capturar huella:', error);
                    Swal.fire('Error', 'No se pudo capturar la huella', 'error');
                });
            }
        });
    });
});
</script>

@endsection