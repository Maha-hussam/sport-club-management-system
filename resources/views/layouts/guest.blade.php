<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sport Club') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .auth-content {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .auth-content .login-card {
            width: 420px;
            max-width: 100%;
            margin: 0;
        }

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
            min-height: 100vh;
            font-family: Figtree, Arial, sans-serif;
            background: radial-gradient(ellipse at top right, #293820 0%, transparent 45%), var(--dark);
            color: var(--text);
        }

        .auth-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .auth-header {
            min-height: 76px;
            padding: 16px 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            border-bottom: 1px solid var(--border);
        }

        .brand {
            color: var(--text);
            text-decoration: none;
            font-size: 23px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .brand span {
            color: var(--green);
        }

        .back-home {
            color: var(--muted);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }

        .back-home:hover {
            color: var(--green);
        }

        .auth-main {
            width: 100%;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .auth-content {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .auth-content .login-card {
            width: 420px;
            max-width: 100%;
            margin: 0 auto;
        }

        .auth-content .register-card {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
        }

        .auth-heading {
            text-align: center;
            margin-bottom: 24px;
        }

        .auth-heading .eyebrow {
            color: var(--green);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .auth-heading h1 {
            margin: 0;
            font-size: clamp(28px, 5vw, 38px);
            line-height: 1.2;
            font-weight: 800;
        }

        .auth-heading p {
            color: var(--muted);
            margin: 10px 0 0;
            font-size: 14px;
        }

        .auth-card {
            width: 100%;
            max-width: 480px;
            padding: 30px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 20px 60px rgb(0 0 0 / 18%);
        }

        .auth-card label {
            color: var(--text);
        }

        .auth-card input:not([type="checkbox"]):not([type="radio"]),
        .auth-card select {
            background: var(--dark);
            color: var(--text);
            border: 1px solid #414a3f;
            border-radius: 7px;
        }

        .auth-card input:focus,
        .auth-card select:focus {
            border-color: var(--green);
            outline-color: var(--green);
        }

        .auth-card a {
            color: var(--green);
        }

        .auth-footer {
            padding: 20px;
            text-align: center;
            color: var(--muted);
            font-size: 12px;
            border-top: 1px solid var(--border);
        }

        @media (max-width: 520px) {
            .auth-header {
                min-height: 66px;
            }

            .auth-card {
                padding: 22px 18px;
            }

            .auth-main {
                padding: 30px 16px;
            }
        }
    </style>
</head>

<body>
    <div class="auth-page">
        <header class="auth-header"> <a href="{{ route('home') }}" class="brand"> SPORT<span>CLUB.</span> </a>

            <a href="{{ route('home') }}" class="back-home"> &larr; Back to Home </a>
        </header>

        <main class="auth-main">

            <div class="auth-content">
                {{ $slot }}
            </div>
        </main>

        <footer class="auth-footer"> &copy; {{ date('Y') }} Sport Club Management System. All rights reserved. </footer>
    </div>
</body>

</html>