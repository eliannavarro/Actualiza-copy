<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Descargar Excel</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/Datas/download.css') }}" rel="stylesheet">

    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="dark-mode-switch">
        <label class="switch">
            <input type="checkbox" />
            <span class="slider round"></span>
        </label>
        <span class="mode-text">Dark mode</span>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        <div class="buttons">
           @if (Auth::user()->rol==='admin')
            <!-- Botón para descargar el ticket -->
            <a href="{{ route('export',$data->id) }}" >Descargar Excel</a>
           @endif
            <!-- Botón para finalizar -->
            @if (Auth::user()->rol==='admin')
                <a href="{{ route('completados.index') }}" class="finalizar">Finalizar</a>
            @elseif (Auth::user()->rol==='user')
                <a href="{{ route('asignados.index') }}" class="finalizar">volver</a>
            @endif
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