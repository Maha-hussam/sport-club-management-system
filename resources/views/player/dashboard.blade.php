<x-app-layout>
    <style>
        .player-dashboard {
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

        .player-container {
            width: 90%;
            max-width: 1000px;
            margin: 0 auto;
        }

        .player-intro {
            margin-bottom: 32px;
        }

        .player-tag {
            display: inline-block;
            margin-bottom: 12px;
            color: var(--green);
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1.5px;
        }

        .player-title {
            color: var(--text);
            font-size: clamp(32px, 5vw, 46px);
            font-weight: bold;
            line-height: 1.2;
        }

        .player-title span {
            color: var(--green);
        }

        .player-description {
            margin-top: 14px;
            color: var(--muted);
            font-size: 15px;
        }

        .player-card {
            padding: 28px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
        }

        .player-card-heading {
            display: flex;
            align-items: center;
            gap: 18px;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--border);
        }

        .player-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 62px;
            height: 62px;
            border-radius: 12px;
            background: #293820;
            color: var(--green);
            font-size: 24px;
            font-weight: bold;
        }

        .player-name {
            color: var(--text);
            font-size: 22px;
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .player-role {
            display: inline-block;
            margin-top: 5px;
            padding: 4px 12px;
            border: 1px solid #465738;
            border-radius: 20px;
            color: var(--green);
            font-size: 12px;
            font-weight: bold;
        }

        .player-details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px;
            padding-top: 24px;
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
            font-weight: 500;
            overflow-wrap: anywhere;
        }

        .player-section {
            margin-top: 28px;
        }

        .section-heading {
            margin-bottom: 16px;
            color: var(--text);
            font-size: 23px;
            font-weight: bold;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .stat-card {
            padding: 24px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
        }

        .stat-number {
            margin-top: 8px;
            color: var(--green);
            font-size: 34px;
            font-weight: bold;
        }

        .matches-list {
            display: grid;
            gap: 14px;
        }

        .match-card {
            padding: 22px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .match-name {
            margin-bottom: 14px;
            color: var(--green);
            font-size: 19px;
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .match-info {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .match-description {
            margin-top: 14px;
            color: var(--muted);
            font-size: 14px;
            overflow-wrap: anywhere;
        }

        .empty-state {
            padding: 30px 22px;
            background: var(--card);
            border: 1px dashed var(--border);
            border-radius: 12px;
            color: var(--muted);
            text-align: center;
        }

        @media (max-width: 600px) {
            .player-dashboard {
                padding: 40px 0;
            }

            .player-card,
            .stat-card,
            .match-card {
                padding: 20px;
            }

            .player-details,
            .stats-grid,
            .match-info {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .player-avatar {
                width: 52px;
                height: 52px;
            }
        }
    </style>

    <section class="player-dashboard">
        <div class="player-container">

            <header class="player-intro">
                <span class="player-tag">YOUR GOALS. YOUR GAME.</span>

                <h1 class="player-title">
                    Welcome back,<br>
                    <span>{{ $player->first_name }}.</span>
                </h1>

                <p class="player-description">
                    Your sporting journey starts here.
                    Keep moving forward!
                </p>
            </header>

            <section class="player-card" aria-label="Player welcome">

                <div class="player-card-heading">
                    <div class="player-avatar" aria-hidden="true">
                        {{ strtoupper(substr($player->first_name, 0, 1)) }}
                    </div>

                    <div style="flex: 1; min-width: 0;">
                        <h2 class="player-name">
                            {{ $player->first_name }} {{ $player->last_name }}
                        </h2>

                        <p class="player-description" style="margin-top: 6px;">
                            Ready for your next challenge?
                        </p>
                    </div>


                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="{{ route('player.profile') }}"
                            style="display: inline-block; padding: 10px 16px; background: #b7f34a; color: #101510; border-radius: 8px; font-size: 14px; font-weight: bold; text-decoration: none; white-space: nowrap;">
                            My Profile →
                        </a>

                        <a href="{{ route('player.matches') }}"
                            style="display: inline-block; padding: 10px 16px; background: #1a211a; color: #b7f34a; border: 1px solid #465738; border-radius: 8px; font-size: 14px; font-weight: bold; text-decoration: none; white-space: nowrap;">
                            My Matches →
                        </a>
                    </div>

                </div>

            </section>

            <section class="player-section">
                <h2 class="section-heading">Your Activity</h2>

                <div class="stats-grid">
                    <div class="stat-card">
                        <span class="detail-label">
                            Total Assigned Matches
                        </span>

                        <p class="stat-number">{{ $totalMatches }}</p>
                    </div>

                    <div class="stat-card">
                        <span class="detail-label">
                            Upcoming Matches
                        </span>

                        <p class="stat-number">
                            {{ $upcomingMatches->count() }}
                        </p>
                    </div>
                </div>
            </section>

            <section class="player-section">
                <h2 class="section-heading">Upcoming Matches</h2>

                @if ($upcomingMatches->isNotEmpty())
                    <div class="matches-list">
                        @foreach ($upcomingMatches as $match)
                            <article class="match-card">
                                <h3 class="match-name">
                                    {{ $match->match_name }}
                                </h3>

                                <div class="match-info">
                                    <div>
                                        <span class="detail-label">
                                            Date
                                        </span>

                                        <p class="detail-value">
                                            {{ \Carbon\Carbon::parse($match->match_date)->format('M d, Y') }}
                                        </p>
                                    </div>

                                    <div>
                                        <span class="detail-label">
                                            Time
                                        </span>

                                        <p class="detail-value">
                                            {{ \Carbon\Carbon::parse($match->match_time)->format('h:i A') }}
                                        </p>
                                    </div>

                                    <div>
                                        <span class="detail-label">
                                            Location
                                        </span>

                                        <p class="detail-value">
                                            {{ $match->location }}
                                        </p>
                                    </div>
                                </div>

                                @if ($match->description)
                                    <p class="match-description">
                                        {{ $match->description }}
                                    </p>
                                @endif
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <p>No upcoming matches assigned to you yet.</p>
                        <p style="margin-top: 8px; font-size: 13px;">
                            Check back later for your next game!
                        </p>
                    </div>
                @endif
            </section>

        </div>
    </section>
</x-app-layout>