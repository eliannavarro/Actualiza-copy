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
                <ul class="menu-links">

                    {{-- 🔹 Órdenes --}}
                    <li class="nav-link">
                        <a href="{{ route('asignados.index') }}">
                             <i class='bx bx-briefcase icon'></i>
                             <span class="text nav-text">Órdenes</span>
                        </a>
                    </li>
                    
                    {{-- 🔹 Pendientes (vista principal) --}}
                    <li class="nav-link">
                        <a href="{{ route('datauser.pendientes') }}">
                            <i class='bx bx-list-ul icon'></i>
                            <span class="text nav-text">Pendientes</span>
                        </a>
                    </li>

                    {{-- 🔹 Completados --}}
                    <li class="nav-link">
                        <a href="{{ route('datauser.completados') }}">
                            <i class='bx bx-check-circle icon'></i>
                            <span class="text nav-text">Completados</span>
                        </a>
                    </li>

                </ul>
            </div>

            <div class="bottom-content">
                <li>
                    <a href="#" id="logout-link">
                        <i class='bx bx-log-out icon'></i>
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
            modeSwitch = body.querySelector(".toggle-switch"),
            modeText = body.querySelector(".mode-text");

        // Mantener estado de sidebar
        body.classList.add('no-transition');
        const sidebarState = localStorage.getItem('sidebarOpen');
        if (sidebarState === 'true') sidebar.classList.remove('close');
        window.addEventListener('DOMContentLoaded', () => body.classList.remove('no-transition'));
        toggle.addEventListener("click", () => {
            const isOpen = sidebar.classList.toggle('close');
            localStorage.setItem('sidebarOpen', !isOpen);
        });

        // Dark mode
        if (localStorage.getItem('darkMode') === 'true') {
            body.classList.add('dark');
            modeText.innerText = "Light mode";
        } else {
            modeText.innerText = "Dark mode";
        }
        modeSwitch.addEventListener("click", () => {
            body.classList.toggle("dark");
            const dark = body.classList.contains("dark");
            localStorage.setItem('darkMode', dark);
            modeText.innerText = dark ? "Light mode" : "Dark mode";
        });

        // Logout
        document.addEventListener('DOMContentLoaded', function() {
            const logoutLink = document.getElementById('logout-link');
            logoutLink.addEventListener('click', function(e) {
                e.preventDefault();
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('logout') }}';
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);
                document.body.appendChild(form);
                form.submit();
            });
        });
    </script>
</body>
</html>
