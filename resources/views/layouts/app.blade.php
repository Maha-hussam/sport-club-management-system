<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sport Club | Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --green: #b7f34a;
            --dark: #101510;
            --card: #1a211a;
            --text: #f5f7f2;
            --muted: #a5afa2;
            --border: #303a2e;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--dark);
            color: var(--text);
            font-family: Arial, sans-serif;
            line-height: 1.7;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .min-h-screen {
            min-height: 100vh;
            background: var(--dark) !important;
        }

        .dashboard-header {
            background: var(--card);
            border-bottom: 1px solid var(--border);
        }

        .dashboard-header>div {
            max-width: 1120px;
            margin: auto;
            padding: 24px 0;
        }

        .dashboard-header h1 {
            color: var(--text);
            font-size: 28px;
            font-weight: bold;
        }

        main {
            width: 100%;
            min-height: calc(100vh - 80px);
        }

        @media (max-width: 700px) {
            .dashboard-header>div {
                padding: 20px 5%;
            }
        }
    </style>
</head>

<body>
    <div class="min-h-screen w-full">
        @include('layouts.navigation')

        @isset($header)
            <header class="dashboard-header">
                <div>
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>