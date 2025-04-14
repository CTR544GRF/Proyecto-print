@extends('home_p')

@section('style')
{{ asset('css/user_create.css') }}
@endsection

@section('metaa')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

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
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"></script>
<script>
document.getElementById('scanFingerprint').addEventListener('click', async function() {
    const btn = this;
    btn.disabled = true;
    document.getElementById('fingerprintPreview').textContent = "Capturando huella...";

    try {
        const response = await fetch('/capturar-huella', { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
            document.getElementById('fingerprintData').value = data.fingerprint;
            document.getElementById('fingerprintPreview').textContent = "✅ Huella capturada correctamente";
            document.getElementById('fingerprintPreview').style.color = "green";
        } else {
            throw new Error(data.error || "Error desconocido al capturar la huella");
        }

    } catch (error) {
        console.error('Error:', error);
        document.getElementById('fingerprintPreview').textContent = "❌ Error al capturar huella";
        document.getElementById('fingerprintPreview').style.color = "red";
        
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message
        });
    } finally {
        btn.disabled = false;
    }
});
</script>

@endsection