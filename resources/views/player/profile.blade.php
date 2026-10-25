<x-app-layout>
    <style>
        .profile-page {
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

        .profile-container {
            width: 90%;
            max-width: 850px;
            margin: 0 auto;
        }

        .profile-tag {
            display: inline-block;
            margin-bottom: 12px;
            color: var(--green);
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1.5px;
        }

        .profile-title {
            margin-bottom: 12px;
            color: var(--text);
            font-size: clamp(30px, 5vw, 42px);
            font-weight: bold;
        }

        .profile-description {
            margin-bottom: 30px;
            color: var(--muted);
            font-size: 15px;
        }

        .profile-card {
            padding: 30px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
        }

        .profile-heading {
            display: flex;
            align-items: center;
            gap: 18px;
            padding-bottom: 25px;
            border-bottom: 1px solid var(--border);
        }

        .profile-avatar {
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

        .profile-name {
            color: var(--text);
            font-size: 22px;
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .profile-role {
            display: inline-block;
            margin-top: 6px;
            padding: 4px 12px;
            border: 1px solid #465738;
            border-radius: 20px;
            color: var(--green);
            font-size: 12px;
            font-weight: bold;
        }

        .profile-fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 25px;
            padding-top: 26px;
        }

        .profile-field {
            min-width: 0;
        }

        .field-label {
            display: block;
            margin-bottom: 8px;
            color: var(--muted);
            font-size: 13px;
        }

        .field-value {
            color: var(--text);
            font-size: 15px;
            overflow-wrap: anywhere;
        }

        .profile-note {
            margin-top: 24px;
            padding: 15px;
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--muted);
            font-size: 13px;
        }

        .profile-back {
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

        .profile-back:hover {
            background: #293820;
            border-color: var(--green);
        }

        @media (max-width: 600px) {
            .profile-page {
                padding: 40px 0;
            }

            .profile-card {
                padding: 20px;
            }

            .profile-fields {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .profile-avatar {
                width: 52px;
                height: 52px;
            }
        }
    </style>

    <section class="profile-page">
        <div class="profile-container">

            <a href="{{ route('player.dashboard') }}" class="profile-back">
                &larr; Back to Dashboard
            </a>

            <header>
                <span class="profile-tag">PLAYER ACCOUNT</span>

                <h1 class="profile-title">My Profile</h1>

                <p class="profile-description">
                    View your personal information and account details.
                </p>
            </header>

            <section class="profile-card" aria-label="My personal information">

                <div class="profile-heading">
                    <div class="profile-avatar" aria-hidden="true">
                        {{ strtoupper(substr($player->first_name, 0, 1)) }}
                    </div>

                    <div>
                        <h2 class="profile-name">
                            {{ $player->first_name }}
                            {{ $player->last_name }}
                        </h2>

                        <span class="profile-role">PLAYER</span>
                    </div>
                </div>

                <div class="profile-fields">
                    <div class="profile-field">
                        <span class="field-label">First Name</span>
                        <p class="field-value">{{ $player->first_name }}</p>
                    </div>

                    <div class="profile-field">
                        <span class="field-label">Last Name</span>
                        <p class="field-value">{{ $player->last_name }}</p>
                    </div>

                    <div class="profile-field">
                        <span class="field-label">Email Address</span>
                        <p class="field-value">{{ $player->email }}</p>
                    </div>

                    <div class="profile-field">
                        <span class="field-label">Registration Date</span>
                        <p class="field-value">
                            {{ $player->created_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>

                <div class="profile-note">
                    Your profile is read-only. You cannot edit your personal
                    information from this page.
                </div>
            </section>

        </div>
    </section>
</x-app-layout>