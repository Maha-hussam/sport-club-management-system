<x-app-layout>
    <style>
        .match-details-page {
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

        .details-container {
            width: 90%;
            max-width: 850px;
            margin: 0 auto;
        }

        .details-tag {
            display: inline-block;
            margin-bottom: 12px;
            color: var(--green);
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1.5px;
        }

        .details-title {
            color: var(--text);
            font-size: clamp(30px, 5vw, 42px);
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .details-subtitle {
            margin-top: 12px;
            margin-bottom: 28px;
            color: var(--muted);
            font-size: 15px;
        }

        .details-card {
            padding: 30px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 26px;
        }

        .detail-item {
            min-width: 0;
        }

        .detail-label {
            display: block;
            margin-bottom: 8px;
            color: var(--muted);
            font-size: 13px;
        }

        .detail-value {
            color: var(--text);
            font-size: 16px;
            overflow-wrap: anywhere;
        }

        .description-section {
            margin-top: 28px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
        }

        .description-text {
            color: var(--text);
            font-size: 15px;
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        .details-back {
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
            transition:
                background 0.2s ease,
                border-color 0.2s ease;
        }

        .details-back:hover {
            background: #293820;
            border-color: var(--green);
        }

        @media (max-width: 600px) {
            .match-details-page {
                padding: 40px 0;
            }

            .details-card {
                padding: 22px;
            }

            .details-grid {
                grid-template-columns: 1fr;
                gap: 22px;
            }
        }
    </style>

    <section class="match-details-page">
        <div class="details-container">

            <a href="{{ route('player.matches') }}" class="details-back">
                &larr; Back to My Matches
            </a>

            <header>
                <span class="details-tag">YOUR MATCH</span>

                <h1 class="details-title">
                    {{ $match->match_name }}
                </h1>

                <p class="details-subtitle">
                    View the details of your assigned match.
                </p>
            </header>

            <section class="details-card" aria-label="Match information">

                <div class="details-grid">

                    <div class="detail-item">
                        <span class="detail-label">
                            Match Name
                        </span>

                        <p class="detail-value">
                            {{ $match->match_name }}
                        </p>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">
                            Match Date
                        </span>

                        <p class="detail-value">
                            {{ \Carbon\Carbon::parse($match->match_date)->format('M d, Y') }}
                        </p>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">
                            Match Time
                        </span>

                        <p class="detail-value">
                            {{ \Carbon\Carbon::parse($match->match_time)->format('h:i A') }}
                        </p>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">
                            Location
                        </span>

                        <p class="detail-value">
                            {{ $match->location }}
                        </p>
                    </div>

                </div>

                <div class="description-section">
                    <span class="detail-label">
                        Description
                    </span>

                    <p class="description-text">
                        {{ $match->description ?: 'No description provided.' }}
                    </p>
                </div>

            </section>

        </div>
    </section>
</x-app-layout>