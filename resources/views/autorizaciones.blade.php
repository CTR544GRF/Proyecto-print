@extends ('home_p')



@section('principal_content')

    <div class="form-header">
        <p>Datos de Autorización:</p>
        <p class="date">Hoy 25/6/2024</p>
    </div>

    <form>
        <label for="cedula">Cédula</label>
        <input type="text" id="cedula" value="1003510447">

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" value="Juan Camilo Diaz">

        <label for="motivo">Motivo</label>
        <textarea id="motivo"></textarea>

        <label for="horas">Selecciona las horas a autorizar</label>
        <input type="number" id="horas" value="2">

        <button type="submit">Crear Autorización</button>
    </form>

@endsection
