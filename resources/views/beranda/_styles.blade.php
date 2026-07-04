    <style>
            :root {
                --public-surface: #ffffff;
                --public-muted: #64748b;
                --public-border: rgba(15, 23, 42, 0.08);
                --public-shadow: 0 18px 45px -20px rgba(15, 23, 42, 0.25);
                --public-shadow-soft: 0 10px 30px -15px rgba(15, 23, 42, 0.12);
                --hero-nav-offset: 5.25rem;
            }

            body.public-layout {
                background-color: var(--umk-blue);
            }

            .public-main {
                margin: 0;
                padding: 0;
            }

            [x-cloak] {
                display: none !important;
            }

            /* ── Hero ── */
            .hero-section {
                position: relative;
                min-height: calc(100vh + var(--hero-nav-offset));
                display: flex;
                align-items: center;
                overflow-x: hidden;
                margin-top: calc(-1 * var(--hero-nav-offset));
                padding-top: calc(6rem + var(--hero-nav-offset));
                padding-bottom: 5rem;
                background-color: var(--umk-blue);
                background-image: url('{{ asset('images/hero-kelurahan-mlatinorowito.jpg') }}');
                background-size: cover;
                background-position: center top;
                background-repeat: no-repeat;
            }

            .hero-section::before {
                content: '';
                position: absolute;
                inset: 0;
                z-index: 0;
                background:
                    linear-gradient(180deg, rgba(15, 23, 42, 0.72) 0%, rgba(15, 23, 42, 0.45) 42%, rgba(15, 23, 42, 0.55) 100%),
                    radial-gradient(circle at 85% 15%, rgba(245, 158, 11, 0.18), transparent 45%);
                pointer-events: none;
            }

            .hero-content {
                position: relative;
                z-index: 1;
            }

            .hero-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.45rem 1rem;
                margin-bottom: 1.25rem;
                border-radius: 999px;
                font-size: 0.82rem;
                font-weight: 600;
                letter-spacing: 0.04em;
                text-transform: uppercase;
                color: rgba(255, 255, 255, 0.92);
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.22);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
            }

            .hero-title {
                font-size: clamp(2.35rem, 7vw, 4.25rem);
                letter-spacing: -0.035em;
                line-height: 1.08;
                color: #fff;
                text-shadow: 0 4px 24px rgba(0, 0, 0, 0.35);
            }

            .hero-location {
                font-size: clamp(1rem, 2.5vw, 1.2rem);
                color: rgba(255, 255, 255, 0.88);
                text-shadow: 0 2px 12px rgba(0, 0, 0, 0.35);
            }

            .hero-tagline {
                font-size: clamp(1.05rem, 2.2vw, 1.25rem);
                color: rgba(255, 255, 255, 0.78);
                max-width: 38rem;
                margin-inline: auto;
                line-height: 1.7;
                text-shadow: 0 2px 14px rgba(0, 0, 0, 0.35);
            }

            .hero-actions .btn {
                min-width: 11.5rem;
                padding: 0.85rem 1.6rem;
                font-weight: 600;
            }

            .hero-actions .btn-outline-light {
                border-width: 1.5px;
                border-color: rgba(255, 255, 255, 0.55);
                color: #fff;
                backdrop-filter: blur(6px);
            }

            .hero-actions .btn-outline-light:hover {
                background: rgba(255, 255, 255, 0.14);
                border-color: #fff;
                color: #fff;
            }

            .hero-scroll-hint {
                position: absolute;
                left: 50%;
                bottom: 1.5rem;
                transform: translateX(-50%);
                z-index: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.35rem;
                color: rgba(255, 255, 255, 0.65);
                font-size: 0.75rem;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                animation: hero-bounce 2s ease-in-out infinite;
            }

            .hero-scroll-hint span {
                width: 1.5rem;
                height: 2.4rem;
                border: 1.5px solid rgba(255, 255, 255, 0.45);
                border-radius: 999px;
                position: relative;
            }

            .hero-scroll-hint span::after {
                content: '';
                position: absolute;
                top: 0.45rem;
                left: 50%;
                width: 0.25rem;
                height: 0.45rem;
                transform: translateX(-50%);
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.85);
                animation: hero-scroll-dot 2s ease-in-out infinite;
            }

            @keyframes hero-bounce {
                0%, 100% { transform: translateX(-50%) translateY(0); }
                50% { transform: translateX(-50%) translateY(6px); }
            }

            @keyframes hero-scroll-dot {
                0%, 100% { opacity: 1; transform: translateX(-50%) translateY(0); }
                50% { opacity: 0.35; transform: translateX(-50%) translateY(0.55rem); }
            }

            @media (max-width: 767.98px) {
                .hero-section {
                    background-position: 72% center;
                    padding-bottom: 4.5rem;
                }

                .hero-scroll-hint {
                    display: none;
                }
            }

            /* ── Sections ── */
            .public-section {
                padding: clamp(3.5rem, 8vw, 5.5rem) 0;
                background-color: #f1f5f9;
            }

            .public-section-alt {
                background: linear-gradient(180deg, #f8fafc 0%, #eef2f7 100%);
            }

            .public-section-white {
                background: #ffffff;
            }

            .section-header {
                text-align: center;
                max-width: 42rem;
                margin: 0 auto 3rem;
            }

            .section-eyebrow {
                display: inline-block;
                margin-bottom: 0.75rem;
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.14em;
                text-transform: uppercase;
                color: var(--umk-blue-accent);
            }

            .section-title {
                color: var(--umk-blue);
                font-weight: 800;
                letter-spacing: -0.03em;
                margin-bottom: 0;
            }

            .section-title-accent {
                width: 4.5rem;
                height: 0.35rem;
                background: linear-gradient(90deg, var(--umk-yellow), var(--umk-yellow-hover));
                margin: 1rem auto 0;
                border-radius: 999px;
            }

            .section-lead {
                color: var(--public-muted);
                line-height: 1.75;
                margin-top: 1.25rem;
                margin-bottom: 0;
            }

            /* ── Cards & stats ── */
            .premium-card {
                border: 1px solid var(--public-border);
                box-shadow: var(--public-shadow-soft);
            }

            .premium-card:hover {
                box-shadow: var(--public-shadow);
            }

            .stat-card {
                position: relative;
                overflow: hidden;
            }

            .stat-card::before {
                content: '';
                position: absolute;
                inset: 0 auto 0 0;
                width: 4px;
                background: linear-gradient(180deg, var(--umk-yellow), var(--umk-blue-accent));
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .stat-card:hover::before {
                opacity: 1;
            }

            .stat-number {
                background: linear-gradient(135deg, var(--umk-blue), var(--umk-blue-accent));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                font-size: clamp(2.25rem, 5vw, 3.25rem);
                font-weight: 800;
                line-height: 1.15;
            }

            .member-card .avatar-circle {
                width: 6.25rem;
                height: 6.25rem;
                font-size: 1.5rem;
                font-weight: 700;
                color: #fff;
                box-shadow: 0 12px 28px -10px rgba(15, 23, 42, 0.35);
                border: 3px solid #fff;
            }

            .proker-icon {
                font-size: 2.75rem;
                line-height: 1;
                filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.08));
                transition: transform 0.35s ease;
            }

            .premium-card:hover .proker-icon {
                transform: scale(1.08) rotate(4deg);
            }

            .empty-state-card {
                padding: 3rem 1.5rem;
                text-align: center;
                color: var(--public-muted);
                background: var(--public-surface);
                border: 1px dashed rgba(15, 23, 42, 0.12);
                border-radius: 1.25rem;
            }

            /* ── Kontak ── */
            .kontak-panel {
                height: 100%;
                padding: 1.75rem;
                background: var(--public-surface);
                border: 1px solid var(--public-border);
                border-radius: 1.25rem;
                box-shadow: var(--public-shadow-soft);
            }

            .kontak-item + .kontak-item {
                margin-top: 1.35rem;
                padding-top: 1.35rem;
                border-top: 1px solid rgba(15, 23, 42, 0.06);
            }

            .kontak-item-label {
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: var(--umk-blue-accent);
                margin-bottom: 0.35rem;
            }

            .kontak-map {
                border: 0;
                border-radius: 1.25rem;
                width: 100%;
                height: 100%;
                min-height: 22rem;
                box-shadow: var(--public-shadow-soft);
            }

            .public-meta-line {
                color: var(--public-muted);
                font-size: 0.92rem;
            }
    </style>
