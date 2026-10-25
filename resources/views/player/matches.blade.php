<x-app-layout>
    <style>
        .matches-page {
            --green: #b7f34a;
            --dark: #101510;
            --card: #1a211a;
            --text: #f5f7f2;
            --muted: #a5afa2;
            --border: #303a2e;

            min-height: calc(100vh - 80px);
            padding: 55px 0;
            background: radial-gradient(ellipse at top right,
                    #293820,
                    var(--dark) 55%);
            color: var(--text);
        }

        .matches-container {
            width: 90%;
            max-width: 1000px;
            margin: 0 auto;
        }

        .matches-tag {
            display: inline-block;
            margin-bottom: 12px;
            color: var(--green);
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1.5px;
        }

        .matches-title {
            color: var(--text);
            font-size: clamp(32px, 5vw, 42px);
            font-weight: bold;
        }

        .matches-description {
            margin-top: 12px;
            margin-bottom: 28px;
            color: var(--muted);
            font-size: 15px;
        }

        .matches-summary {
            margin-bottom: 24px;
            padding: 22px 25px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .summary-label {
            color: var(--muted);
            font-size: 13px;
        }

        .summary-number {
            margin-top: 5px;
            color: var(--green);
            font-size: 30px;
            font-weight: bold;
        }

        .matches-list {
            display: grid;
            gap: 18px;
        }

        .match-card {
            padding: 26px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
        }

        .match-title {
            margin-bottom: 20px;
            color: var(--green);
            font-size: 21px;
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .match-details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        .detail-label {
            display: block;
            margin-bottom: 7px;
            color: var(--muted);
            font-size: 13px;
        }

        .detail-value {
            color: var(--text);
            font-size: 15px;
            overflow-wrap: anywhere;
        }

        .match-description {
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid var(--border);
        }

        .empty-state {
            padding: 40px 24px;
            background: var(--card);
            border: 1px dashed var(--border);
            border-radius: 14px;
            text-align: center;
        }

        .empty-title {
            color: var(--text);
            font-size: 19px;
            font-weight: bold;
        }

        .empty-description {
            margin-top: 10px;
            color: var(--muted);
            font-size: 14px;
        }

        .matches-back {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: fit-content;
            margin: 0 0 30px auto;
            padding: 10px 16px;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--green);
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.2s ease, border-color 0.2s ease;
        }

        .matches-back:hover {
            background: #293820;
            border-color: var(--green);
        }

        @media (max-width: 600px) {
            .matches-page {
                padding: 40px 0;
            }

            .match-card {
                padding: 20px;
            }

            .match-details {
                grid-template-columns: 1fr;
                gap: 18px;
            }
        }
    </style>

    <section class="matches-page">
        <div class="matches-container">

            <a href="{{ route('player.dashboard') }}" class="matches-back">
                &larr; Back to Dashboard
            </a>

            <header>
                <span class="matches-tag">YOUR GAME. YOUR JOURNEY.</span>

                <h1 class="matches-title">My Matches</h1>

                <p class="matches-description">
                    View all matches assigned to your player account.
                </p>
            </header>

            <section class="matches-summary">
                <p class="summary-label">Total Assigned Matches</p>

                <p class="summary-number">{{ $matches->count() }}</p>
            </section>

            <div class="matches-list">
                @forelse ($matches as $match)
                    <article class="match-card">
                        <h2 class="match-title">
                            {{ $match->match_name }}
                        </h2>

                        <div class="match-details">
                            <div>
                                <span class="detail-label">Match Date</span>

                                <p class="detail-value">
                                    {{ \Carbon\Carbon::parse($match->match_date)->format('M d, Y') }}
                                </p>
                            </div>

                            <div>
                                <span class="detail-label">Match Time</span>

                                <p class="detail-value">
                                    {{ \Carbon\Carbon::parse($match->match_time)->format('h:i A') }}
                                </p>
                            </div>

                            <div>
                                <span class="detail-label">Location</span>

                                <p class="detail-value">
                                    {{ $match->location }}
                                </p>
                            </div>
                        </div>

                        <div class="match-description">
                            <span class="detail-label">Description</span>

                            <p class="detail-value">
                                {{ $match->description ?: 'No description provided.' }}
                            </p>
                        </div>

                        <div style="margin-top: 22px;">
                            <a href="{{ route('player.matches.show', ['matchId' => $match->id]) }}"
                                style="display: inline-block; padding: 11px 18px; background: #b7f34a; color: #101510; border-radius: 8px; font-weight: bold; font-size: 14px; text-decoration: none;">
                                View Match Details →
                            </a>
                        </div>

                    </article>
                @empty
                    <div class="empty-state">
                        <h2 class="empty-title">No Matches Yet</h2>

                        <p class="empty-description">
                            You have no matches assigned to you yet.
                            Please check back later.
                        </p>
                    </div>
                @endforelse
            </div>

        </div>
    </section>
</x-app-layout>