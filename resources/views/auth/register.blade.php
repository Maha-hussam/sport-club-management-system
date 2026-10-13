<x-guest-layout>
    <style>
        .register-card h1 {
            text-align: center;
            width: 100%;
            display: block;
        }

        body {
            background: #101510;
            color: #f5f7f2;
        }

        .register-card {
            max-width: 480px;
            margin: 30px auto;
            padding: 30px;
            background: #1a211a;
            border: 1px solid #303a2e;
            border-radius: 14px;
        }

        .register-card label {
            color: #f5f7f2;
        }

        .register-card input,
        .register-card select {
            width: 100%;
            box-sizing: border-box;
            display: block;
            margin-top: 6px;
            margin-bottom: 10px;
            padding: 10px;
            background: #101510;
            color: white;
            border: 1px solid #414a3f;
            border-radius: 6px;
        }

        .register-card h1,
        .register-card .accent {
            color: #b7f34a;
        }

        .register-card .submit {
            background: #b7f34a;
            color: #101510;
            padding: 10px 18px;
            margin-top: 20px;
            border-radius: 6px;
            font-weight: bold;
            display: block;
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
        }

        .register-card .submit:hover {
            background: #a5df3f;
        }

        .register-card .login-link {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            width: 100%;
            margin-top: 18px;
            text-align: center;
            font-size: 12px;
            color: #b0b8aa;
        }

        .register-card .login-link a {
            color: #b7f34a;
            font-size: 12px;
        }
    </style>

    <div class="register-card">
        <h1 class="text-2xl font-bold text-center">Join Sport Club</h1>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            @foreach ([
                    'first_name' => ['First Name', 'text'],
                    'last_name' => ['Last Name', 'text'],
                    'email' => ['Email', 'email'],
                    'password' => ['Password', 'password'],
                    'password_confirmation' => ['Confirm Password', 'password'],
                ] as $name => [$label, $type])
                <div class="mb-5">
                    <x-input-label :for="$name" :value="$label" />
                    <x-text-input :id="$name" :type="$type" :name="$name" :value="$type === 'password' ? '' : old($name)"
                        :autocomplete="$name === 'first_name' ? 'given-name' : ($name === 'last_name' ? 'family-name' : ($name === 'email' ? 'email' : 'new-password'))" required />
                    <x-input-error :messages="$errors->get($name)" class="mt-2" />
                </div>
            @endforeach

            <div class="mb-5">
                <x-input-label for="role" value="Role" />
                <select id="role" name="role" required>
                    <option value="">Select your role</option>
                    <option value="trainer" @selected(old('role') === 'trainer')>Trainer</option>
                    <option value="player" @selected(old('role') === 'player')>Player</option>
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <button type="submit" class="submit w-full">Create Account</button>

            <div class="login-link">
                <span>Already registered?</span>
                <a href="{{ route('login') }}" class="accent underline">Sign In</a>
            </div>
        </form>
    </div>
</x-guest-layout>