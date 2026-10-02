<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Simulador de Recuperação Tributária') | {{ config('simulador.empresa') }}</title>

    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <style>
        :root {
            --sim-primaria: #0b5394;
            --sim-primaria-escura: #073763;
            --sim-destaque: #1e9e5a;
        }

        body {
            background: #f1f4f8;
            font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .sim-topo {
            background: linear-gradient(135deg, var(--sim-primaria-escura), var(--sim-primaria));
            color: #fff;
            padding: 2.5rem 0 6rem;
        }

        .sim-topo h1 {
            font-weight: 700;
            font-size: 2rem;
        }

        .sim-topo .lead {
            opacity: .9;
        }

        .sim-marca {
            color: #fff;
            font-weight: 700;
            letter-spacing: .02em;
        }

        .sim-marca:hover {
            color: #fff;
            text-decoration: none;
        }

        .sim-conteudo {
            margin-top: -4.5rem;
            padding-bottom: 3rem;
        }

        .sim-card {
            border: 0;
            border-radius: .75rem;
            box-shadow: 0 10px 30px rgba(7, 55, 99, .12);
        }

        .sim-rodape {
            color: #6c757d;
            font-size: .85rem;
            padding: 1.5rem 0 2rem;
        }

        @media print {
            .sim-topo { padding: 1rem 0; background: none; color: #000; }
            .sim-conteudo { margin-top: 0; }
            .sim-card { box-shadow: none; }
            .no-print { display: none !important; }
        }
    </style>

    @stack('css')
</head>
<body>
    <header class="sim-topo">
        <div class="container">
            <a class="sim-marca" href="{{ route('simulador.index') }}">
                <i class="fas fa-calculator mr-1"></i> {{ config('simulador.empresa') }}
            </a>
            <div class="mt-4">
                @yield('cabecalho')
            </div>
        </div>
    </header>

    <main class="container sim-conteudo">
        @yield('content')
    </main>

    <footer class="container sim-rodape text-center">
        <p class="mb-1">{{ config('simulador.aviso_legal') }}</p>
        <p class="mb-0">&copy; {{ date('Y') }} {{ config('simulador.empresa') }}</p>
    </footer>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @stack('js')
</body>
</html>
