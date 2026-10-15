<x-app-layout>
    <x-slot name="header">
        <div class="page-heading">
            <p class="page-eyebrow">SPORT CLUB / MANAGEMENT</p>
            <h2 class="page-title">Player Management</h2>
        </div>
    </x-slot>

    <div class="players-page">
        <div class="players-container">
            <a href="{{ route('trainer.dashboard') }}" class="back-link">
                ← Back to Dashboard
            </a>
            @if (session('success'))
                <div class="success-message">
                    <span class="success-icon">✓</span>
                    {{ session('success') }}
                </div>
            @endif

            <div class="page-intro">
                <div>
                    <p class="section-label">TEAM OVERVIEW</p>
                    <h3 class="section-title">Players List</h3>
                    <p class="section-description">
                        View and manage registered club players.
                    </p>
                </div>

                <a href="{{ route('trainer.players.create') }}" class="primary-button">
                    <span class="button-icon">+</span> Add Player
                </a>
            </div>

            <div class="players-card">
                <div class="table-wrapper">
                    <table class="players-table">
                        <thead>
                            <tr>
                                <th>Player</th>
                                <th>Email Address</th>
                                <th>Joined Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($players as $player)
                                <tr>
                                    <td>
                                        <div class="player-cell">
                                            <div class="player-avatar">
                                                {{ strtoupper(substr($player->first_name, 0, 1)) }}
                                            </div>
                                            <div class="player-name">
                                                <span>{{ $player->first_name }} {{ $player->last_name }}</span>
                                                <small>Club Player</small>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="email-cell">{{ $player->email }}</td>
                                    <td class="date-cell">{{ $player->created_at->format('Y-m-d') }}</td>

                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('trainer.players.edit', $player) }}"
                                                class="edit-button">Edit</a>

                                            <form method="POST" action="{{ route('trainer.players.destroy', $player) }}"
                                                onsubmit="return confirm('Are you sure you want to delete this player?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="delete-button">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <div class="empty-icon">◎</div>
                                            <h4>No players yet</h4>
                                            <p>Add your first player to build your club roster.</p>
                                            <a href="{{ route('trainer.players.create') }}" class="primary-button">
                                                Add First Player
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($players->hasPages())
                    <div class="pagination-wrapper">{{ $players->links() }}</div>
                @endif
            </div>

            <p class="page-footer">SPORT CLUB <span>•</span> PLAYER MANAGEMENT</p>
        </div>
    </div>

    <style>
        .players-page {
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
        .players-container {
            max-width: 1120px;
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

        .page-intro {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 26px;
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

        .page-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 16px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: var(--card);
            color: var(--text);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            transition: border-color 0.2s, color 0.2s;
        }

        .back-button:hover {
            border-color: var(--lime);
            color: var(--lime);
        }

        .back-button:focus-visible {
            outline: 2px solid var(--lime);
            outline-offset: 3px;
        }

        .primary-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 5px 8px;
            border: 1px solid var(--lime);
            border-radius: 9px;
            background: var(--lime);
            color: #101510;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            white-space: nowrap;
        }

        .primary-button:hover {
            background: #c8ff69;
        }

        .button-icon {
            font-size: 20px;
        }

        .players-card {
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--card);
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .players-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .players-table thead {
            background: #202920;
        }

        .players-table th {
            padding: 17px 20px;
            color: var(--muted);
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .players-table td {
            padding: 18px 20px;
            border-top: 1px solid var(--border);
            font-size: 14px;
            vertical-align: middle;
        }

        .players-table tbody tr:hover {
            background: #202820;
        }

        .player-cell {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 190px;
        }

        .player-avatar {
            display: flex;
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            border: 1px solid #46553b;
            border-radius: 12px;
            background: #293623;
            color: var(--lime);
            font-weight: 700;
        }

        .player-name {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .player-name span {
            font-weight: 600;
        }

        .player-name small {
            color: var(--muted);
            font-size: 11px;
        }

        .email-cell {
            color: #d4dbd0;
        }

        .date-cell {
            color: var(--muted);
            white-space: nowrap;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .action-buttons form {
            margin: 0;
        }

        .edit-button,
        .delete-button {
            padding: 0;
            border: 0;
            background: none;
            font: inherit;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .edit-button {
            color: var(--lime);
            text-decoration: none;
        }

        .delete-button {
            color: #ff8585;
        }

        .edit-button:hover,
        .delete-button:hover {
            text-decoration: underline;
        }

        .success-message {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
            padding: 14px 17px;
            border: 1px solid #456638;
            border-radius: 10px;
            background: #1d2b1b;
            color: #d6ffb7;
        }

        .success-icon {
            display: grid;
            width: 24px;
            height: 24px;
            place-items: center;
            border-radius: 50%;
            background: var(--lime);
            color: var(--dark);
        }

        .empty-state {
            padding: 45px 20px;
            text-align: center;
        }

        .empty-icon {
            color: var(--lime);
            font-size: 38px;
        }

        .empty-state h4 {
            margin: 8px 0;
            font-size: 19px;
        }

        .empty-state p {
            margin: 8px 0 20px;
            color: var(--muted);
            font-size: 14px;
        }

        .pagination-wrapper {
            padding: 18px 20px;
            border-top: 1px solid var(--border);
        }

        .pagination-wrapper nav {
            display: flex;
            justify-content: space-between;
            gap: 12px;
        }

        .pagination-wrapper a,
        .pagination-wrapper span {
            color: var(--text);
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
            .players-page {
                padding: 26px 16px;
            }

            .page-title {
                font-size: 24px;
            }

            .page-intro {
                align-items: flex-start;
                flex-direction: column;
            }

            .players-table th,
            .players-table td {
                padding: 14px 12px;
            }

            .primary-button {
                padding: 11px 14px;
            }
        }
    </style>
</x-app-layout>