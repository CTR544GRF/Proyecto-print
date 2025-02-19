<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inknut+Antiqua:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <title>LOGIN</title>
</head>

<body>

    <form class="welcome" action="{{ url('login') }}" method="POST">

    @csrf
        <h2>¡Bienvenido al Sistema de Control de Acceso welcome !</h2>

        <div class="from_group">
            <label class="from_label" for="email">Correo Electronico</label>
            <input class="from_input" type="email" name="email" placeholder="Ingresa tu Correo Electronico" required>
        </div>

        <div class="from_group">
            <label class="from_label" for="password">Contraseña</label>
            <input class="from_input" type="password" name="password" placeholder="Ingresa tu Contraseña" required>
        </div>

        <button type="submit">Ingresar</button>

        <a class="recovery_pasword" href="">¿Olvidaste tu Contraseña?</a>
    </form>

</body>

</html>