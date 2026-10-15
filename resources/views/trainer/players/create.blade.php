<x-app-layout>
    <x-slot name="header">
        <div class="form-page-heading">
            <p class="form-eyebrow">SPORT CLUB / MANAGEMENT</p>
            <h2 class="form-page-title">Add Player</h2>
        </div>
    </x-slot>

    <div class="player-form-page">
        <div class="player-form-container">

            <a href="{{ route('trainer.players.index') }}" class="back-link">
                ← Back to Players
            </a>

            <div class="form-intro">
                <div class="form-avatar">+</div>

                <div>
                    <p class="form-section-label">NEW REGISTRATION</p>
                    <h3 class="form-section-title">Add a New Player</h3>
                    <p class="form-description">
                        Enter the player's details to register them with the club.
                    </p>
                </div>
            </div>

            <div class="player-form-card">
                <form method="POST" action="{{ route('trainer.players.store') }}">
                    @csrf

                    <div class="form-grid">
                        <div class="form-field">
                            <label for="first_name">First Name</label>
                            <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}"
                                autocomplete="given-name" required
                                class="form-input @error('first_name') input-error @enderror">

                            @error('first_name')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-field">
                            <label for="last_name">Last Name</label>
                            <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}"
                                autocomplete="family-name" required
                                class="form-input @error('last_name') input-error @enderror">

                            @error('last_name')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="email">Email Address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email"
                            required class="form-input @error('email') input-error @enderror">

                        @error('email')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-divider"></div>

                    <div class="password-section">
                        <h4>Account Security</h4>
                        <p>
                            Create a password for the player's account and confirm it below.
                        </p>
                    </div>

                    <div class="form-field">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                            class="form-input @error('password') input-error @enderror">

                        @error('password')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-field">
                        <label for="password_confirmation">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            autocomplete="new-password" required class="form-input">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="form-submit-button">
                            Create Player
                        </button>

                        <a href="{{ route('trainer.players.index') }}" class="form-cancel-button">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <p class="form-footer">SPORT CLUB <span>•</span> PLAYER MANAGEMENT</p>
        </div>
    </div>

    <style>
        .player-form-page {
            --lime: #b7f34a;
            --dark: #101510;
            --card: #1a211a;
            --text: #f5f7f2;
            --muted: #a5afa2;
            --border: #303a2e;
            min-height: calc(100vh - 100px);
            padding: 32px 24px 45px;
            background: var(--dark);
            color: var(--text);
        }

        .form-page-heading,
        .player-form-container {
            max-width: 850px;
            margin: auto;
        }

        .form-eyebrow,
        .form-section-label {
            margin: 0 0 6px;
            color: var(--lime);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .form-page-title {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            margin-bottom: 25px;
            padding: 10px 17px;
            background: #202920;
            color: #b7f34a;
            border: 1px solid #617a43;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 3px 8px rgb(0 0 0 / 18%);
            transition: all 0.2s ease;
        }

        .back-link:hover {
            background: #b7f34a;
            color: #101510;
            border-color: #b7f34a;
            transform: translateY(-2px);
            box-shadow: 0 5px 14px rgb(183 243 74 / 18%);
        }

        .back-link:focus-visible {
            outline: 2px solid #b7f34a;
            outline-offset: 4px;
        }

        .form-intro {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 25px;
        }

        .form-avatar {
            display: grid;
            width: 54px;
            height: 54px;
            flex-shrink: 0;
            place-items: center;
            border: 1px solid #46553b;
            border-radius: 15px;
            background: #293623;
            color: var(--lime);
            font-size: 27px;
            font-weight: 700;
        }

        .form-section-title {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
        }

        .form-description {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 13px;
        }

        .player-form-card {
            padding: 30px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--card);
            box-shadow: 0 12px 30px rgb(0 0 0 / 12%);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .form-field {
            min-width: 0;
            margin-bottom: 22px;
        }

        .form-field label {
            display: block;
            margin-bottom: 8px;
            color: #e5eade;
            font-size: 13px;
            font-weight: 600;
        }

        .form-input {
            display: block;
            width: 100%;
            min-height: 45px;
            padding: 11px 13px;
            border: 1px solid #3a4637;
            border-radius: 8px;
            outline: none;
            background: #121812;
            color: var(--text);
            font: inherit;
            font-size: 14px;
            transition: .2s;
        }

        .form-input:focus {
            border-color: var(--lime);
            box-shadow: 0 0 0 3px rgb(183 243 74 / 10%);
        }

        .form-input.input-error {
            border-color: #ff8585;
        }

        .field-error {
            margin: 7px 0 0;
            color: #ff9999;
            font-size: 12px;
        }

        .form-divider {
            height: 1px;
            margin: 4px 0 23px;
            background: var(--border);
        }

        .password-section {
            margin-bottom: 22px;
        }

        .password-section h4 {
            margin: 0 0 5px;
            font-size: 16px;
            font-weight: 700;
        }

        .password-section p {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.6;
        }

        .form-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 8px;
            padding-top: 23px;
            border-top: 1px solid var(--border);
        }

        .form-submit-button,
        .form-cancel-button {
            display: inline-flex;
            min-height: 43px;
            align-items: center;
            justify-content: center;
            padding: 11px 19px;
            border-radius: 8px;
            font: inherit;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: .2s;
        }

        .form-submit-button {
            border: 1px solid var(--lime);
            background: var(--lime);
            color: var(--dark);
        }

        .form-submit-button:hover {
            background: #c8ff69;
            transform: translateY(-1px);
        }

        .form-cancel-button {
            border: 1px solid var(--border);
            background: transparent;
            color: var(--muted);
        }

        .form-cancel-button:hover {
            background: #252e24;
            color: var(--text);
        }

        .form-footer {
            margin: 25px 0 0;
            color: #74806e;
            font-size: 10px;
            letter-spacing: 1.5px;
            text-align: center;
        }

        .form-footer span {
            padding: 0 5px;
            color: var(--lime);
        }

        @media (max-width: 600px) {
            .player-form-page {
                padding: 25px 16px 35px;
            }

            .form-page-title {
                font-size: 24px;
            }

            .player-form-card {
                padding: 21px 17px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .form-actions {
                align-items: stretch;
                flex-direction: column;
            }

            .form-submit-button,
            .form-cancel-button {
                width: 100%;
            }
        }
    </style>
</x-app-layout>