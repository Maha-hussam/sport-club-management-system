<x-app-layout>
    <x-slot name="header">
        <div class="page-heading">
            <p class="page-eyebrow">SPORT CLUB / MANAGEMENT</p>
            <h2 class="page-title">Create Match</h2>
        </div>
    </x-slot>

    <div class="matches-page">
        <div class="matches-container">
            <a href="{{ route('trainer.matches.index') }}" class="back-link">
                ← Back to Matches
            </a>

            <div class="form-card">
                <div class="page-intro">
                    <div>
                        <p class="section-label">NEW MATCH</p>
                        <h3 class="section-title">Match Information</h3>
                        <p class="section-description">
                            Enter the details of the new club match.
                        </p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="error-message">
                        <strong>Please correct the following errors:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('trainer.matches.store') }}">
                    @csrf

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="match_name">Match Name</label>
                            <input id="match_name" name="match_name" type="text" value="{{ old('match_name') }}"
                                required maxlength="255" class="@error('match_name') input-error @enderror">

                            @error('match_name')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="location">Location</label>
                            <input id="location" name="location" type="text" value="{{ old('location') }}" required
                                maxlength="255" class="@error('location') input-error @enderror">

                            @error('location')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="match_date">Match Date</label>
                            <input id="match_date" name="match_date" type="date" value="{{ old('match_date') }}"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="match_time">Match Time</label>
                            <input id="match_time" name="match_time" type="time" value="{{ old('match_time') }}"
                                required>
                        </div>

                        <div class="form-group full-width">
                            <label for="description">Description (Optional)</label>
                            <textarea id="description" name="description" rows="5"
                                maxlength="2000">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('trainer.matches.index') }}" class="cancel-button">
                            Cancel
                        </a>

                        <button type="submit" class="primary-button">
                            Create Match
                        </button>
                    </div>
                </form>
            </div>

            <p class="page-footer">
                SPORT CLUB <span>•</span> MATCH MANAGEMENT
            </p>
        </div>
    </div>

    <style>
        .matches-page {
            --lime: #b7f34a;
            --dark: #101510;
            --card: #1a211a;
            --text: #f5f7f2;
            --muted: #a5afa2;
            --border: #303a2e;
            min-height: calc(100vh - 100px);
            padding: 36px 24px;
            background: var(--dark);
            color: var(--text);
        }

        .page-heading,
        .matches-container {
            max-width: 900px;
            margin: auto;
        }

        .page-eyebrow,
        .section-label {
            margin: 0 0 6px;
            color: var(--lime);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .page-title {
            margin: 0;
            color: var(--text);
            font-size: 28px;
            font-weight: 700;
        }

        .form-group input.input-error,
        .form-group textarea.input-error {
            border-color: #ff8585;
        }

        .field-error {
            margin: 0;
            color: #ff8585;
            font-size: 12px;
        }

        .back-link {
            display: inline-flex;
            margin-bottom: 25px;
            padding: 10px 17px;
            background: #202920;
            color: var(--lime);
            border: 1px solid #617a43;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
        }

        .back-link:hover {
            background: var(--lime);
            color: var(--dark);
        }

        .form-card {
            padding: 28px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--card);
        }

        .page-intro {
            margin-bottom: 25px;
        }

        .section-title {
            margin: 0;
            font-size: 23px;
            font-weight: 700;
        }

        .section-description {
            margin-top: 5px;
            color: var(--muted);
            font-size: 14px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            color: var(--text);
            font-size: 13px;
            font-weight: 600;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            outline: none;
            background: #101510;
            color: var(--text);
            font: inherit;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: var(--lime);
            box-shadow: 0 0 0 2px rgb(183 243 74 / 12%);
        }

        .form-group textarea {
            resize: vertical;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 28px;
        }

        .primary-button,
        .cancel-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 18px;
            border: 1px solid var(--lime);
            border-radius: 9px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .primary-button {
            background: var(--lime);
            color: var(--dark);
        }

        .primary-button:hover {
            background: #c8ff69;
        }

        .cancel-button {
            border-color: var(--border);
            background: transparent;
            color: var(--text);
        }

        .cancel-button:hover {
            border-color: var(--lime);
            color: var(--lime);
        }

        .error-message {
            margin-bottom: 22px;
            padding: 15px 18px;
            border: 1px solid #8f4141;
            border-radius: 9px;
            background: #321d1d;
            color: #ffc1c1;
            font-size: 13px;
        }

        .error-message ul {
            margin: 8px 0 0;
            padding-left: 20px;
        }

        .page-footer {
            margin-top: 25px;
            color: #74806e;
            font-size: 10px;
            letter-spacing: 1.5px;
            text-align: center;
        }

        .page-footer span {
            padding: 0 5px;
            color: var(--lime);
        }

        @media (max-width: 640px) {
            .matches-page {
                padding: 26px 16px;
            }

            .form-card {
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .full-width {
                grid-column: auto;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .primary-button,
            .cancel-button {
                width: 100%;
            }
        }
    </style>
</x-app-layout>