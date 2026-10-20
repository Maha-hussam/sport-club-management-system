<x-app-layout>
    <x-slot name="header">
        <div class="page-heading">
            <p class="page-eyebrow">SPORT CLUB / MANAGEMENT</p>
            <h2 class="page-title">Match Management</h2>
        </div>
    </x-slot>

    <div class="matches-page">
        <div class="matches-container">
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
                    <p class="section-label">MATCH OVERVIEW</p>
                    <h3 class="section-title">Matches List</h3>
                    <p class="section-description">
                        View and manage scheduled club matches.
                    </p>
                </div>

                <a href="{{ route('trainer.matches.create') }}" class="primary-button">
                    <span class="button-icon">+</span> Add Match
                </a>
            </div>

            <div class="matches-card">
                <div class="table-wrapper">
                    <table class="matches-table">
                        <thead>
                            <tr>
                                <th>Match</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($matches as $match)
                                <tr>
                                    <td>
                                        <div class="match-name">
                                            <span>{{ $match->match_name }}</span>
                                            <small>Club Match</small>
                                        </div>
                                    </td>

                                    <td class="date-cell">
                                        {{ $match->match_date }}
                                    </td>

                                    <td class="time-cell">
                                        {{ \Carbon\Carbon::parse($match->match_time)->format('H:i') }}
                                    </td>

                                    <td class="location-cell">
                                        {{ $match->location }}
                                    </td>


                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('trainer.matches.show', $match) }}" class="view-button">
                                                Details
                                            </a>

                                            <a href="{{ route('trainer.matches.edit', $match) }}" class="edit-button">
                                                Edit
                                            </a>

                                            <form method="POST" action="{{ route('trainer.matches.destroy', $match) }}"
                                                onsubmit="return confirm('Are you sure you want to delete this match?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="delete-button">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <div class="empty-icon">◎</div>
                                            <h4>No matches yet</h4>
                                            <p>Create your first match to organize club activities.</p>

                                            <a href="{{ route('trainer.matches.create') }}" class="primary-button">
                                                Add First Match
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($matches->hasPages())
                    <div class="pagination-wrapper">
                        {{ $matches->links() }}
                    </div>
                @endif
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
            margin-bottom: 25px;
            padding: 10px 17px;
            background: #202920;
            color: var(--lime);
            border: 1px solid #617a43;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .back-link:hover {
            background: var(--lime);
            color: var(--dark);
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

        .primary-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 10px 15px;
            border: 1px solid var(--lime);
            border-radius: 9px;
            background: var(--lime);
            color: var(--dark);
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .primary-button:hover {
            background: #c8ff69;
        }

        .button-icon {
            font-size: 20px;
        }

        .matches-card {
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--card);
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .matches-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .matches-table thead {
            background: #202920;
        }

        .matches-table th {
            padding: 17px 20px;
            color: var(--muted);
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .matches-table td {
            padding: 18px 20px;
            border-top: 1px solid var(--border);
            font-size: 14px;
            vertical-align: middle;
        }

        .matches-table tbody tr:hover {
            background: #202820;
        }

        .match-name {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 130px;
        }

        .match-name span {
            font-weight: 600;
        }

        .match-name small {
            color: var(--muted);
            font-size: 11px;
        }

        .date-cell,
        .time-cell,
        .location-cell {
            color: #d4dbd0;
            white-space: nowrap;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 12px;
            white-space: nowrap;
        }

        .action-buttons form {
            margin: 0;
        }

        .view-button,
        .edit-button,
        .delete-button {
            padding: 0;
            border: 0;
            background: none;
            font: inherit;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .view-button,
        .edit-button {
            color: var(--lime);
        }

        .delete-button {
            color: #ff8585;
        }

        .action-buttons a:hover,
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
            .matches-page {
                padding: 26px 16px;
            }

            .page-title {
                font-size: 24px;
            }

            .page-intro {
                align-items: flex-start;
                flex-direction: column;
            }

            .matches-table th,
            .matches-table td {
                padding: 14px 12px;
            }
        }
    </style>
</x-app-layout>