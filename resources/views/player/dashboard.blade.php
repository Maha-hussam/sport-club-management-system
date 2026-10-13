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
            padding: 65px 0;
            background:
                radial-gradient(ellipse at top right, #293820, var(--dark) 55%);
            color: var(--text);
        }

        .player-container {
            width: 90%;
            max-width: 900px;
            margin: 0 auto;
        }

        .player-intro {
            margin-bottom: 35px;
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
            padding: 32px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
        }

        .player-card-heading {
            display: flex;
            align-items: center;
            gap: 18px;
            padding-bottom: 26px;
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
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            padding-top: 25px;
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

        @media (max-width: 600px) {
            .player-dashboard {
                padding: 45px 0;
            }

            .player-card {
                padding: 22px;
            }

            .player-details {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .player-avatar {
                width: 52px;
                height: 52px;
            }
        }
    </style>

    @php
        $user = auth()->user();
        $fullName = trim($user->first_name . ' ' . $user->last_name);
    @endphp

    <section class="player-dashboard">
        <div class="player-container">

            <div class="player-intro">
                <span class="player-tag">YOUR GOALS. YOUR GAME.</span>

                <h1 class="player-title">
                    Welcome back,<br>
                    <span>{{ $user->first_name }}.</span>
                </h1>

                <p class="player-description">
                    Your sporting journey starts here. Keep moving forward!
                </p>
            </div>

            <section class="player-card" aria-label="Player account information">

                <div class="player-card-heading">
                    <div class="player-avatar" aria-hidden="true">
                        {{ strtoupper(substr($user->first_name, 0, 1)) }}
                    </div>

                    <div>
                        <h2 class="player-name">{{ $fullName }}</h2>
                        <span class="player-role">PLAYER ACCOUNT</span>
                    </div>
                </div>

                <div class="player-details">
                    <div>
                        <span class="detail-label">Email Address</span>
                        <p class="detail-value">{{ $user->email }}</p>
                    </div>

                    <div>
                        <span class="detail-label">Account Role</span>
                        <p class="detail-value">{{ ucfirst($user->role) }}</p>
                    </div>
                </div>

            </section>

        </div>
    </section>
</x-app-layout>