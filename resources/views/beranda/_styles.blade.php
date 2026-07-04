    <style>
            [x-cloak] {
                display: none !important;
            }

            .hero-section {
                position: relative;
                min-height: 100vh;
                display: flex;
                align-items: center;
                overflow: hidden;
                padding-top: 100px;
                padding-bottom: 4rem;
                background-color: var(--umk-blue);
                background-image: url('{{ asset('images/hero-kelurahan-mlatinorowito.jpg') }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            }

            .hero-section::before {
                content: '';
                position: absolute;
                inset: 0;
                z-index: 0;
                background: linear-gradient(
                    to bottom,
                    rgba(0, 0, 0, 0.6) 0%,
                    rgba(0, 0, 0, 0.45) 45%,
                    rgba(0, 0, 0, 0.3) 100%
                );
                pointer-events: none;
            }

            .hero-content {
                position: relative;
                z-index: 1;
            }

            .hero-title {
                font-size: clamp(2.5rem, 8vw, 4.5rem);
                letter-spacing: -0.03em;
                line-height: 1.1;
                color: #fff;
                text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
            }

            .hero-section .lead,
            .hero-section p {
                text-shadow: 0 2px 16px rgba(0, 0, 0, 0.5);
            }

            @media (max-width: 767.98px) {
                .hero-section {
                    /* Fokus ke bangunan kantor kelurahan (kanan-tengah foto) saat crop mobile */
                    background-position: 72% center;
                }
            }

            .section-title {
                color: var(--umk-blue);
                font-weight: 800;
                letter-spacing: -0.02em;
            }

            .section-title-accent {
                width: 80px;
                height: 6px;
                background: linear-gradient(90deg, var(--umk-yellow), var(--umk-yellow-hover));
                margin: 1rem auto 0;
                border-radius: 10px;
            }

            .stat-number {
                background: linear-gradient(135deg, var(--umk-blue), var(--umk-blue-accent));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                font-size: clamp(2.5rem, 6vw, 3.5rem);
                font-weight: 800;
                line-height: 1.2;
            }

            .proker-icon {
                font-size: 3rem;
                line-height: 1;
                filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
                transition: transform 0.3s ease;
            }
            
            .premium-card:hover .proker-icon {
                transform: scale(1.1) rotate(5deg);
            }

            .avatar-circle {
                width: 100px;
                height: 100px;
                font-size: 2rem;
                font-weight: 700;
                color: #fff;
                box-shadow: 0 10px 20px -5px rgba(0,0,0,0.2);
            }

            .kontak-map {
                border: 0;
                border-radius: 1.25rem;
                width: 100%;
                height: 400px;
                box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
            }
    </style>
