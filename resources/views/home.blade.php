<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sport Club | Home</title>

    <style>
        :root {
            --green: #b7f34a;
            --dark: #101510;
            --card: #1a211a;
            --text: #f5f7f2;
            --muted: #a5afa2;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: var(--dark);
            color: var(--text);
            line-height: 1.7;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .container {
            width: 90%;
            max-width: 1120px;
            margin: auto;
        }

        header {
            border-bottom: 1px solid #293129;
        }

        .navbar,
        nav,
        .actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .navbar {
            min-height: 80px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .logo span,
        h1 span {
            color: var(--green);
        }

        nav a {
            color: var(--muted);
            font-size: 14px;
        }

        nav a:hover {
            color: var(--green);
        }

        .nav-button,
        .button {
            display: inline-block;
            padding: 11px 22px;
            border: 1px solid var(--green);
            border-radius: 8px;
        }

        .nav-button {
            color: var(--green);
        }

        .nav-button:hover {
            background: var(--green);
            color: var(--dark);
        }

        .hero {
            padding: 100px 0;
            background: radial-gradient(ellipse at top right, #293820, var(--dark) 60%);
        }

        .hero-content {
            max-width: 760px;
        }

        .tag {
            display: inline-block;
            margin-bottom: 20px;
            color: var(--green);
            font-size: 13px;
            font-weight: bold;
        }

        h1 {
            font-size: clamp(40px, 7vw, 72px);
            line-height: 1.15;
        }

        .hero p {
            max-width: 600px;
            margin: 24px 0 30px;
            color: var(--muted);
        }

        .actions {
            justify-content: flex-start;
            flex-wrap: wrap;
        }

        .button {
            font-weight: bold;
        }

        .primary {
            background: var(--green);
            color: var(--dark);
        }

        .secondary {
            border-color: #414a3f;
        }

        .button:hover {
            opacity: .85;
        }

        .features {
            padding: 70px 0;
            background: #151b15;
        }

        .section-title {
            margin-bottom: 35px;
            text-align: center;
        }

        .section-title h2 {
            font-size: clamp(26px, 4vw, 36px);
        }

        .section-title p,
        .feature-card p {
            color: var(--muted);
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
        }

        .feature-card {
            padding: 28px;
            border: 1px solid #303a2e;
            border-radius: 14px;
            background: var(--card);
        }

        .feature-card:hover {
            border-color: var(--green);
        }

        .feature-card h3 {
            margin: 12px 0 8px;
        }

        .feature-icon {
            font-size: 28px;
        }

        footer {
            padding: 22px;
            border-top: 1px solid #293129;
            color: var(--muted);
            text-align: center;
            font-size: 13px;
        }

        @media (max-width: 700px) {
            .navbar {
                padding: 18px 0;
                flex-wrap: wrap;
            }

            nav {
                gap: 14px;
            }

            .hero {
                padding: 70px 0;
            }

            .feature-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="container navbar">
            <a href="{{ route('home') }}" class="logo">SPORT<span>CLUB.</span></a>

            <nav aria-label="Main navigation">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('register') }}">Sign Up</a>
                <a href="{{ route('login') }}" class="nav-button">Sign In</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container hero-content">
                <span class="tag">YOUR GOALS. YOUR GAME.</span>

                <h1>Train Hard.<br>Get <span>Stronger.</span><br>Go Further.</h1>

                <p>
                    Welcome to Sport Club, where players and trainers
                    connect to build skills, improve performance, and
                    achieve their sporting goals together.
                </p>

                <div class="actions">
                    <a href="{{ route('register') }}" class="button primary">
                        Join Our Club &rarr;
                    </a>
                    <a href="{{ route('login') }}" class="button secondary">
                        Sign In
                    </a>
                </div>
            </div>
        </section>

        <section class="features">
            <div class="container">
                <div class="section-title">
                    <h2>Everything Starts With You</h2>
                    <p>A place to connect, develop, and move forward.</p>
                </div>

                <div class="feature-grid">
                    <article class="feature-card">
                        <div class="feature-icon">&#9917;</div>
                        <h3>For Players</h3>
                        <p>Create your account, access your dashboard,
                            and take the next step in your sporting journey.</p>
                    </article>

                    <article class="feature-card">
                        <div class="feature-icon">&#127947;</div>
                        <h3>For Trainers</h3>
                        <p>Join the club as a trainer and access a dashboard
                            designed for your role.</p>
                    </article>

                    <article class="feature-card">
                        <div class="feature-icon">&#127942;</div>
                        <h3>One Club, One Goal</h3>
                        <p>Bring players and trainers together in an
                            environment built around progress and achievement.</p>
                    </article>
                </div>
            </div>
        </section>
    </main>

    <footer>
        &copy; {{ date('Y') }} Sport Club Management System. All rights reserved.
    </footer>
</body>

</html>