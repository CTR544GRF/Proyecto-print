@extends ('home_p')

@section('style')
{{ asset('css/forms.css') }}
@endsection

@section('panel') Crear Autorización @endsection
@section('descripcion') Completa el formulario para autorizar horas a un usuario. @endsection

@section('principal_content')
<form action="{{ route('autorizaciones.store') }}" method="POST">
    @csrf
    <div class="form-group" >
        <label for="user_id">Cédula del Usuario</label>
        <select id="user_id" name="user_id" required>
            <option value="" disabled selected>Seleccione un usuario</option>
            @foreach ($usuarios as $usuario)
                <option value="{{ $usuario->id }}" data-nombre="{{ $usuario->nombre }}">
                    {{ $usuario->numero_documento }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group" >
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" readonly>
    </div>

    <div class="form-group">
        <label for="motivo">Motivo de Autorización</label>
        <textarea id="motivo" name="motivo" rows="3" required></textarea>
    </div>

    <div class="form-group" id="horas">
        <label for="horas_autorizadas">Horas a Autorizar</label>
        <input type="number" id="horas_autorizadas" name="horas_autorizadas" min="1" required>
    </div>

    <button type="submit">Guardar Autorización</button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#user_id').change(function() {
            var nombre = $(this).find(':selected').data('nombre');
            $('#nombre').val(nombre);
        });
    });
</script>
@endsection