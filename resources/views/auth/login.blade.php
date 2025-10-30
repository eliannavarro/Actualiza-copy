<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Apptualiza</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="css/LoginStyle.css">
    <link rel="stylesheet" href="{{ asset('css/Datas/indexDataUser.css') }}">

    <!----===== Boxicons CSS ===== -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div>
        <a href="https://rib.com.co/wp/" class="volver btn btn-primary">
            Volver a RIB Logisticas S.A.S
        </a>
    </div>

    <div class="dark-mode-switch">
        <label class="switch">
            <input type="checkbox" />
            <span class="slider round"></span>
        </label>
        <span class="mode-text">Dark mode</span>
    </div>

    <div class="login-container">

        <div class="login-form-section">
            <h2>Iniciar Sesión</h2><br>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <input
                        id="cedula"
                        type="text"
                        class="form-control @error('cedula') is-invalid @enderror"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Correo Electrónico"
                        required
                        autocomplete="off"
                        autofocus
                    >
                    @error('cedula')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <input
                        id="password"
                        type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        name="password"
                        placeholder="Contraseña"
                        required
                        autocomplete="off"
                    >
                    @error('password')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class='bx bx-log-in'></i>
                    Ingresar
                </button>
            </form>
        </div>

        <div class="login-brand-section">
            <img class="brand-logo" src="{{ asset('img/LOGO_RIB_R.png') }}" alt="Logo RIB Logísticas">
        </div>
    </div>


    <script>
        const checkbox = document.querySelector('.dark-mode-switch input[type="checkbox"]');
        const modeText = document.querySelector('.dark-mode-switch .mode-text');

        // Check for existing dark mode preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark');
            checkbox.checked = true;
            modeText.textContent = 'Light mode';
        }

        checkbox.addEventListener('change', () => {
            document.body.classList.toggle('dark');

            if (document.body.classList.contains('dark')) {
                localStorage.setItem('darkMode', 'true');
                modeText.textContent = 'Light mode';
            } else {
                localStorage.setItem('darkMode', 'false');
                modeText.textContent = 'Dark mode';
            }
        });
    </script>
</body>
</html>
