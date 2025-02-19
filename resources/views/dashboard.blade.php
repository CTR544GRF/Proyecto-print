@extends ('home_p')

@section('style')
{{ asset('css/tablas.css') }}
@endsection

@section('principal_content')
<div class="tabla">
    <input class="form" id="myInput" type="text" placeholder="Buscar ...">
    <table>
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Nombre de Usuario</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Motivo</th>
            </tr>
        </thead>
        <tbody id="myTable">
            @foreach ($autorizaciones as $index => $autorizacion)
            <tr>
                <td data-label="Cédula">{{ $autorizacion->user->numero_documento }}</td> <!-- Muestra la cédula del usuario -->
                <td data-label="Nombre de Usuario">{{ $autorizacion->user->nombre }}</td> <!-- Muestra el nombre del usuario relacionado -->
                <td data-label="Fecha">{{ $autorizacion->fecha }}</td>
                <td data-label="Estado">{{ $autorizacion->estado }}</td>
                <td data-label="Motivo">{{ $autorizacion->motivo }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>

<script>
    $(document).ready(function() {
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endsection