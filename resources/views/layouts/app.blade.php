<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!----======== Favicon ======== -->
    <link rel="icon" href="{{ asset('img/FLATICON_RIB.svg') }}" type="image/svg+xml">

    <!----======== CSS ======== -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!----===== Boxicons CSS ===== -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=star_rate" />
    <title>@yield('title')</title>

    @yield('style')
</head>
<body>
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <div class="text logo-text">
                    <span class="name">RIB CONTROL</span>
                </div>
            </div>

            <i class='bx bx-chevron-right toggle'></i>
        </header>

        <div class="menu-bar">
            <div class="menu">

                <form action="{{ route('sidebar.search') }}" method="GET">
                    @csrf
                    <li class="search-box">
                        <i class='bx bx-search icon'></i>
                        <input
                            type="text"
                            name="buscador-sidebar"
                            class="search-sidebar"
                            placeholder="Search..."
                            value="{{ request('buscador-sidebar') }}">
                        <ul id="sugerencias-sidebar" class="suggestions"></ul>
                    </li>
                </form>

                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="{{ route('users.index') }}">
                            <i class='bx bx-user icon' ></i>
                            <span class="text nav-text">Usuarios</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="{{ route('asignar.index') }}">
                            <i class='bx bxs-edit icon'></i>
                            <span class="text nav-text">Asignar </span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="{{ route('desasignar.index') }}">
                            <i class='bx bxs-edit icon'></i>
                            <span class="text nav-text">Desasignar</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="{{ route('schedule.store') }}">
                            <i class='bx bx-list-check icon' ></i>
                            <span class="text nav-text">Agendar</span>
                        </a>
                    </li>


                    <li class="nav-link">
                        <a href="{{ route('completados.index') }}">
                            <i class='bx bx-list-check icon' ></i>
                            <span class="text nav-text">Completados</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="{{ route('import.import') }}">
                            <i class='bx bx-upload icon' ></i>
                            <span class="text nav-text">Importar Excel</span>
                        </a>
                    </li>


                    <li class="nav-link">
                        <a href="{{ route('export') }}">
                            <i class='bx bx-download icon' ></i>
                            <span class="text nav-text">Descargar Excel</span>
                        </a>
                    </li>

                </ul>
            </div>

            <div class="bottom-content">
                <li class="">
                    <a href="#" id="logout-link">
                        <i class='bx bx-log-out icon' ></i>
                        <span class="text nav-text">Cerrar sesión</span>
                    </a>
                </li>

                <li class="mode">
                    <div class="sun-moon" style="margin-top: 6px;">
                        <i class='bx bx-moon icon moon'></i>
                        <i class='bx bx-sun icon sun'></i>
                    </div>
                    <span class="mode-text text">Dark mode</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>

            </div>
        </div>
    </nav>


    <div class="content">
        @if (session('error-sidebar'))
            <div class="alert alert-danger" role="alert">
                <i class="bx bx-error"></i> {{ session('error-sidebar') }}
            </div>
        @endif
        @yield('content')
    </div>

    @yield('scripts')

    <script>
        const body = document.querySelector('body'),
            sidebar = body.querySelector('nav'),
            toggle = body.querySelector(".toggle"),
            searchBtn = body.querySelector(".search-box"),
            modeSwitch = body.querySelector(".toggle-switch"),
            modeText = body.querySelector(".mode-text");

        // Deshabilitar transiciones al cargar la página (para evitar parpadeo)
        body.classList.add('no-transition');

        // Función para verificar el estado inicial de la sidebar
        function loadSidebarState() {
            const sidebarState = localStorage.getItem('sidebarOpen'); // Obtener el estado de la barra lateral
            if (sidebarState === 'true') {
                sidebar.classList.remove('close'); // Mantenerla abierta si estaba abierta
            } else {
                sidebar.classList.add('close'); // Mantenerla cerrada si estaba cerrada
            }
        }

        // Función para guardar el estado de la sidebar en localStorage
        function saveSidebarState(isOpen) {
            localStorage.setItem('sidebarOpen', isOpen);
        }

        // Llamar a la función al cargar la página
        loadSidebarState();

        // Rehabilitar las transiciones después de cargar
        window.addEventListener('DOMContentLoaded', () => {
            body.classList.remove('no-transition');
        });

        // Listener para el botón de toggle de la sidebar
        toggle.addEventListener("click", () => {
            const isOpen = sidebar.classList.toggle('close'); // Alternar la clase "close"
            saveSidebarState(!isOpen); // Guardar el estado actualizado (inverso porque `toggle` devuelve `true` si se agrega la clase)
        });

        searchBtn.addEventListener("click", () => {
            sidebar.classList.remove("close");
            saveSidebarState(true); // Guardar que la barra está abierta
        });

        // Modo oscuro (ya configurado en el ejemplo anterior)
        if (localStorage.getItem('darkMode') === 'true') {
            body.classList.add('dark');
            modeText.innerText = "Light mode";
        } else {
            modeText.innerText = "Dark mode";
        }

        modeSwitch.addEventListener("click", () => {
            body.classList.toggle("dark");

            if (body.classList.contains("dark")) {
                localStorage.setItem('darkMode', 'true');
                modeText.innerText = "Light mode";
            } else {
                localStorage.setItem('darkMode', 'false');
                modeText.innerText = "Dark mode";
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutLink = document.getElementById('logout-link');

            logoutLink.addEventListener('click', function(e) {
                e.preventDefault();  // Evita la acción por defecto del enlace

                // Crea el formulario de cierre de sesión
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('logout') }}'; // Genera la URL de logout usando Blade

                // Agrega el token CSRF al formulario
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Agrega el formulario al cuerpo y envíalo
                document.body.appendChild(form);
                form.submit();  // Envia el formulario
            });
        });
    </script>
</body>
</html>
