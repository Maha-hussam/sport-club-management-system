<x-app-layout>
    <style>
        .trainer-dashboard {
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

        .trainer-container {
            width: 90%;
            max-width: 900px;
            margin: 0 auto;
        }

        .trainer-intro {
            margin-bottom: 35px;
        }

        .trainer-tag {
            display: inline-block;
            margin-bottom: 12px;
            color: var(--green);
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1.5px;
        }

        .trainer-title {
            color: var(--text);
            font-size: clamp(32px, 5vw, 46px);
            font-weight: bold;
            line-height: 1.2;
        }

        .trainer-title span {
            color: var(--green);
        }

        .trainer-description {
            margin-top: 14px;
            color: var(--muted);
            font-size: 15px;
        }

        .trainer-card {
            padding: 32px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
        }

        .trainer-card-heading {
            display: flex;
            align-items: center;
            gap: 18px;
            padding-bottom: 26px;
            border-bottom: 1px solid var(--border);
        }

        .trainer-avatar {
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

        .trainer-name {
            color: var(--text);
            font-size: 22px;
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .trainer-role {
            display: inline-block;
            margin-top: 5px;
            padding: 4px 12px;
            border: 1px solid #465738;
            border-radius: 20px;
            color: var(--green);
            font-size: 12px;
            font-weight: bold;
        }

        .trainer-details {
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
            .trainer-dashboard {
                padding: 45px 0;
            }

            .trainer-card {
                padding: 22px;
            }

            .trainer-details {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .trainer-avatar {
                width: 52px;
                height: 52px;
            }
        }
    </style>

    @php
        $user = auth()->user();
        $fullName = trim($user->first_name . ' ' . $user->last_name);
    @endphp

    <section class="trainer-dashboard">
        <div class="trainer-container">

            <div class="trainer-intro">
                <span class="trainer-tag">YOUR GOALS. YOUR GAME.</span>

                <h1 class="trainer-title">
                    Welcome back,<br>
                    <span>{{ $user->first_name }}.</span>
                </h1>

                <p class="trainer-description">
                    Welcome to your trainer account. Keep inspiring progress!
                </p>
            </div>

            <section class="trainer-card" aria-label="Trainer account information">

                <div class="trainer-card-heading">
                    <div class="trainer-avatar" aria-hidden="true">
                        {{ strtoupper(substr($user->first_name, 0, 1)) }}
                    </div>

                    <div>
                        <h2 class="trainer-name">{{ $fullName }}</h2>
                        <span class="trainer-role">TRAINER ACCOUNT</span>
                    </div>
                </div>

                <div class="trainer-details">
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