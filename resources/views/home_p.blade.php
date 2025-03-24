<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield ('tittle') </title>
    <link rel="stylesheet" href="{{ asset('css/home_style.css') }}">
    <link rel="stylesheet" href=" @yield('style') ">

</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="profile">
                <div class="avatar">JD</div>
                <h2>Juan Diaz</h2>
                <p>Almacenista</p>
            </div>
            <nav class="menu">
                <a href="{{ route('autorizaciones.create') }}"><button>Autorizar</button></a>
                <a href="{{ route('usuarios.create') }}"><button>Crear Usuario</button></a>
                <a href="{{ route('dashboard') }}"><button> Ver Autorizaciones</button></a>
                <button>Ver Entradas</button>
            </nav>
        </aside>

        <!-- Contenido Principal -->
        <main class="content">


            <h1> @yield('panel')</h1>
            <p class="subtext"> @yield('descripcion')</p>

            <div class="form-container">
                <div class="form-header">
                    <p>Datos de Autorización:</p>
                    <p class="date">Hoy 25/6/2024</p>
                </div>
                @yield('principal_content')
            </div>
            
        
        </main>
    </div>
</body>
</html>