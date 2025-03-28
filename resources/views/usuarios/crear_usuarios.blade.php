@extends('home_p')

@section('style')
<link rel="stylesheet" href="{{ asset('css/user_create.css') }}">
@endsection

@section('panel') Crear Usuario @endsection
@section('descripcion') Completa el formulario para registrar un nuevo usuario y capturar su huella dactilar. @endsection

@section('principal_content')

<form action="{{ route('usuarios.store') }}" method="POST">
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

    <div class="form-group" id="password-container">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password">
    </div>

    <!-- Captura de Huella -->
    <div class="form-group">
        <label>Huella Dactilar</label>
        <button type="button" id="scanFingerprint">Capturar Huella</button>
        <input type="hidden" id="fingerprintData" name="fingerprint_data">
        <div id="fingerprintPreview"></div>
        <div id="sensorStatus"></div>
    </div>

    <button type="submit">Registrar Usuario</button>
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('scanFingerprint').addEventListener('click', function() {
    let sensorStatus = document.getElementById('sensorStatus');
    sensorStatus.innerHTML = 'Escaneando...';

    fetch('/api/capturar-huella') // Ruta al controlador Laravel
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('fingerprintData').value = data.fingerprint;
                sensorStatus.innerHTML = '<span style="color: green;">Huella capturada correctamente</span>';
            } else {
                sensorStatus.innerHTML = '<span style="color: red;">Error al capturar huella</span>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            sensorStatus.innerHTML = '<span style="color: red;">Error al conectar con el sensor</span>';
        });
});
</script>

@endsection