<x-app-layout>
    <x-slot name="header">
        <div class="page-heading">
            <p class="page-eyebrow">SPORT CLUB / MANAGEMENT</p>
            <h2 class="page-title">Match Details</h2>
        </div>
    </x-slot>

    <div class="matches-page">
        <div class="matches-container">
            <a href="{{ route('trainer.matches.index') }}" class="back-link">
                ← Back to Matches
            </a>

            @if (session('success'))
                <div class="success-message">{{ session('success') }}</div>
            @endif

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

            <div class="details-card">
                <p class="section-label">MATCH INFORMATION</p>
                <h3 class="section-title">{{ $match->match_name }}</h3>

                <div class="details-grid">
                    <div class="detail-item">
                        <span>Match Date</span>
                        <strong>{{ $match->match_date }}</strong>
                    </div>

                    <div class="detail-item">
                        <span>Match Time</span>
                        <strong>{{ substr($match->match_time, 0, 5) }}</strong>
                    </div>

                    <div class="detail-item">
                        <span>Location</span>
                        <strong>{{ $match->location }}</strong>
                    </div>

                    <div class="detail-item full-width">
                        <span>Description</span>
                        <p>{{ $match->description ?: 'No description provided.' }}</p>
                    </div>
                </div>

                <div class="players-section">
                    <p class="section-label">TEAM ROSTER</p>
                    <h4>Assigned Players ({{ $match->players->count() }})</h4>

                    @forelse ($match->players as $player)
                        <div class="player-item">
                            <div class="player-avatar">
                                {{ strtoupper(substr($player->first_name, 0, 1)) }}
                            </div>

                            <div>
                                <strong>
                                    {{ $player->first_name }} {{ $player->last_name }}
                                </strong>
                                <p>{{ $player->email }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="empty-message">
                            No players have been assigned to this match yet.
                        </p>
                    @endforelse
                </div>

                <div class="players-section">
                    <p class="section-label">PLAYER MANAGEMENT</p>
                    <h4>Assign Players</h4>

                    <p class="form-description">
                        Select the players who will participate in this match.
                        Saving will replace the current assignments with your selection.
                    </p>

                    <form action="{{ route('trainer.matches.players.update', $match) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="player-selection-list">
                            @forelse ($players as $player)
                                <label class="player-option">
                                    <input type="checkbox" name="players[]" value="{{ $player->id }}" @checked(
                                        in_array(
                                            $player->id,
                                            old(
                                                'players',
                                                $match->players->pluck('id')->all()
                                            )
                                        )
                                    )>

                                    <div class="player-avatar">
                                        {{ strtoupper(substr($player->first_name, 0, 1)) }}
                                    </div>

                                    <div class="player-info">
                                        <strong>
                                            {{ $player->first_name }}
                                            {{ $player->last_name }}
                                        </strong>
                                        <p>{{ $player->email }}</p>
                                    </div>
                                </label>
                            @empty
                                <p class="empty-message">
                                    No players are available to assign.
                                </p>
                            @endforelse
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="primary-button">
                                Save Player Assignments
                            </button>
                        </div>
                    </form>
                </div>

                <div class="form-actions">
                    <a href="{{ route('trainer.matches.edit', $match) }}" class="secondary-button">
                        Edit Match Information
                    </a>
                </div>
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
            margin: 0 0 8px;
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

        .back-link {
            display: inline-block;
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

        .details-card {
            padding: 30px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--card);
        }

        .section-title {
            margin: 0 0 25px;
            font-size: 26px;
            font-weight: 700;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        .detail-item {
            padding: 18px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #151b14;
        }

        .detail-item span {
            display: block;
            margin-bottom: 8px;
            color: var(--muted);
            font-size: 12px;
        }

        .detail-item strong {
            font-size: 15px;
            overflow-wrap: anywhere;
        }

        .detail-item p {
            margin: 0;
            color: #d4dbd0;
            font-size: 14px;
            white-space: pre-line;
            overflow-wrap: anywhere;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .players-section {
            margin-top: 32px;
            padding-top: 25px;
            border-top: 1px solid var(--border);
        }

        .players-section h4 {
            margin: 0 0 18px;
            font-size: 19px;
        }

        .player-item,
        .player-option {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 14px 0;
            border-bottom: 1px solid var(--border);
        }

        .player-avatar {
            display: flex;
            flex-shrink: 0;
            width: 42px;
            height: 42px;
            align-items: center;
            justify-content: center;
            border: 1px solid #46553b;
            border-radius: 12px;
            background: #293623;
            color: var(--lime);
            font-weight: 700;
        }

        .player-item strong,
        .player-option strong {
            font-size: 14px;
        }

        .player-item p,
        .player-option p {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 12px;
        }

        .player-option {
            cursor: pointer;
        }

        .player-option:hover {
            background: #202920;
        }

        .player-option input[type="checkbox"] {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            accent-color: var(--lime);
            cursor: pointer;
        }

        .player-info {
            min-width: 0;
            overflow-wrap: anywhere;
        }

        .form-description {
            margin: 0 0 15px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.6;
        }

        .empty-message {
            padding: 18px;
            border: 1px dashed var(--border);
            border-radius: 9px;
            color: var(--muted);
            font-size: 14px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 25px;
        }

        .primary-button,
        .secondary-button {
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

        .secondary-button {
            background: transparent;
            color: var(--lime);
        }

        .secondary-button:hover {
            background: #293623;
        }

        .success-message,
        .error-message {
            margin-bottom: 20px;
            padding: 14px 17px;
            border-radius: 10px;
        }

        .success-message {
            border: 1px solid #456638;
            background: #1d2b1b;
            color: #d6ffb7;
        }

        .error-message {
            border: 1px solid #8f4545;
            background: #301b1b;
            color: #ffcaca;
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

            .details-card {
                padding: 20px;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }

            .full-width {
                grid-column: auto;
            }
        }
    </style>
</x-app-layout>