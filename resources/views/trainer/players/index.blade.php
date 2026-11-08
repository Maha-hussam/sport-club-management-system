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

            <div class="filters-card">
                <form method="GET" action="{{ route('trainer.players.index') }}">
                    <div class="filters-grid">

                        <div class="filter-group search-group">
                            <label for="search">Search Players</label>
                            <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}"
                                placeholder="Search by name or email...">
                        </div>

                        <div class="filter-group">
                            <label for="registered_from">Registered From</label>
                            <input type="date" id="registered_from" name="registered_from"
                                value="{{ $filters['registered_from'] ?? '' }}">
                        </div>

                        <div class="filter-group">
                            <label for="registered_to">Registered To</label>
                            <input type="date" id="registered_to" name="registered_to"
                                value="{{ $filters['registered_to'] ?? '' }}">
                        </div>

                        <div class="filter-group">
                            <label for="matches_count">Assigned Matches</label>
                            <input type="number" id="matches_count" name="matches_count" min="0"
                                value="{{ $filters['matches_count'] ?? '' }}" placeholder="Any number">
                        </div>

                        <div class="filter-group">
                            <label for="sort_by">Sort By</label>
                            <select id="sort_by" name="sort_by">
                                <option value="created_at" {{ ($filters['sort_by'] ?? 'created_at') === 'created_at' ? 'selected' : '' }}>
                                    Registration Date
                                </option>

                                <option value="first_name" {{ ($filters['sort_by'] ?? 'created_at') === 'first_name' ? 'selected' : '' }}>
                                    First Name
                                </option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="sort_direction">Sort Direction</label>
                            <select id="sort_direction" name="sort_direction">
                                <option value="desc" {{ ($filters['sort_direction'] ?? 'desc') === 'desc' ? 'selected' : '' }}>
                                    Descending
                                </option>

                                <option value="asc" {{ ($filters['sort_direction'] ?? 'desc') === 'asc' ? 'selected' : '' }}>
                                    Ascending
                                </option>
                            </select>
                        </div>

                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="filter-button">
                            Search & Filter
                        </button>

                        <a href="{{ route('trainer.players.index') }}" class="clear-button">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>

            <div class="players-card">
                <div class="table-wrapper">
                    <table class="players-table">
                        <thead>
                            <tr>
                                <th>Player</th>
                                <th>Email Address</th>
                                <th>Matches</th>
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
                                                @if ($player->profile_image)
                                                    <img src="{{ asset('storage/' . $player->profile_image) }}"
                                                        alt="{{ $player->first_name }} profile picture">
                                                @else
                                                    <span>
                                                        {{ strtoupper(substr($player->first_name, 0, 1)) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="player-name">
                                                <span>{{ $player->first_name }} {{ $player->last_name }}</span>
                                                <small>Club Player</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="email-cell">{{ $player->email }}</td>
                                    <td>{{ $player->matches_count }}</td>
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
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <div class="empty-icon">◎</div>
                                            @if (
                                                    !empty($filters['search']) ||
                                                    !empty($filters['registered_from']) ||
                                                    !empty($filters['registered_to']) ||
                                                    isset($filters['matches_count'])
                                                )
                                                <h4>No players found</h4>
                                                <p>Try adjusting your search or filters.</p>
                                                <a href="{{ route('trainer.players.index') }}" class="primary-button">
                                                    Clear Filters
                                                </a>
                                            @else
                                                <h4>No players yet</h4>
                                                <p>Add your first player to build your club roster.</p>
                                                <a href="{{ route('trainer.players.create') }}" class="primary-button">
                                                    Add First Player
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        <div class="pagination-count">
                            Showing
                            <strong>{{ $players->firstItem() ?? 0 }}–{{ $players->lastItem() ?? 0 }}</strong>
                            of
                            <strong>{{ $players->total() }}</strong>
                            players
                        </div>

                        <div class="pagination-page">
                            Page {{ $players->currentPage() }}
                            of {{ $players->lastPage() }}
                        </div>
                    </div>

                    <div class="pagination-links">
                        @if ($players->hasPages())

                            @if ($players->onFirstPage())
                                <span class="page-btn disabled">‹</span>
                            @else
                                <a href="{{ $players->previousPageUrl() }}" class="page-btn">
                                    ‹
                                </a>
                            @endif

                            @foreach ($players->getUrlRange(1, $players->lastPage()) as $page => $url)
                                @if ($page == $players->currentPage())
                                    <span class="page-btn active">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($players->hasMorePages())
                                <a href="{{ $players->nextPageUrl() }}" class="page-btn">
                                    ›
                                </a>
                            @else
                                <span class="page-btn disabled">›</span>
                            @endif

                        @endif
                    </div>

                </div>
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

        .pagination-info {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 15px;
            color: var(--muted);
            font-size: 13px;
            flex-wrap: wrap;
        }

        .pagination-info p {
            margin: 0;
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

        .filters-card {
            margin-bottom: 22px;
            padding: 22px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--card);
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-group label {
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            min-height: 42px;
            padding: 9px 11px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #202920;
            color: var(--text);
            font-size: 13px;
            color-scheme: dark;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: 2px solid var(--lime);
            outline-offset: 2px;
        }

        .filter-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 20px;
        }

        .filter-button,
        .clear-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 9px 17px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .filter-button {
            border: 1px solid var(--lime);
            background: var(--lime);
            color: var(--dark);
        }

        .filter-button:hover {
            background: #c8ff69;
        }

        .clear-button {
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text);
        }

        .clear-button:hover {
            border-color: var(--lime);
            color: var(--lime);
        }

        @media (max-width: 768px) {
            .filters-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 480px) {
            .filters-grid {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                align-items: stretch;
                flex-direction: column;
            }
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

        .player-avatar {
            overflow: hidden;
        }

        .player-avatar img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
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
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 20px 24px;
            border-top: 1px solid var(--border);
            background: var(--card);
        }

        .pagination-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .pagination-count {
            font-size: 13px;
            color: var(--text-muted);
        }

        .pagination-count strong {
            color: var(--text);
            font-weight: 700;
        }

        .pagination-page {
            font-size: 12px;
            color: var(--text-muted);
        }

        .pagination-links {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .page-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--card);
            color: var(--text);
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .page-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--hover);
        }

        .page-btn.active {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .page-btn.disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        @media (max-width: 600px) {
            .pagination-wrapper {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
                padding: 18px;
            }

            .pagination-info {
                width: 100%;
            }

            .pagination-links {
                width: 100%;
                overflow-x: auto;
            }
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