<x-guest-layout>
    <style>
        body {
            background: #101510;
            color: #f5f7f2;
        }

        .login-card .forgot-link {
            margin-left: auto;
            text-align: right;
            white-space: nowrap;
        }

        .login-card {
            width: 420px;
            margin: 30px auto;
            padding: 24px;
            background: #1a211a;
            border: 1px solid #303a2e;
            border-radius: 14px;
        }

        .login-card label {
            color: #f5f7f2;
        }

        .login-card input:not([type="checkbox"]) {
            width: 100%;
            margin-top: 8px;
            margin-bottom: 4px;
            padding: 8px 10px;
            background: #101510;
            color: white;
            border: 1px solid #414a3f;
            border-radius: 6px;
        }

        .login-card h1,
        .login-card a {
            color: #b7f34a;
        }

        .login-card h1 {
            text-align: center;
            margin-bottom: 8px;
        }

        .login-card .submit {
            background: #b7f34a;
            color: #101510;
            padding: 10px 28px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            display: block;
            margin: 0 auto;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .login-card .submit:hover {
            background: #a5df3f;
        }

        .login-card .mb-3 {
            margin-bottom: 18px;
        }

        .login-card label.flex {
            margin-top: 8px;
            margin-bottom: 24px;
        }

        .login-card .flex.items-center.justify-between {
            margin-top: 8px;
        }

        .login-card p.text-center.text-sm {
            margin-top: 24px;
        }
    </style>

    <div class="login-card">
        <h1 class="text-2xl font-bold text-center">Welcome Back</h1>
        <x-auth-session-status class="mb-3" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus
                    autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mb-3">
                <x-input-label for="password" value="Password" />
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end">
                <button type="submit" class="submit">Sign In</button>
            </div>

            <p style="text-align: center; margin-top: 18px; font-size: 12px; color: #b0b8aa;">
                Don't have an account?
                <a href="{{ route('register') }}" style="color: #b7f34a; font-size: 12px;" class="underline">Sign Up</a>
            </p>

        </form>
    </div>
</x-guest-layout>