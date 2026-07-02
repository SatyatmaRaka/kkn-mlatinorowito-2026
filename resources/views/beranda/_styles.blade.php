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
                background: linear-gradient(135deg, var(--umk-blue) 0%, #1e3a8a 50%, var(--umk-blue-accent) 100%);
            }

            .hero-section::before {
                content: '';
                position: absolute;
                inset: 0;
                background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1.5px, transparent 1.5px);
                background-size: 32px 32px;
                pointer-events: none;
            }
            
            .hero-section::after {
                content: '';
                position: absolute;
                width: 80vw;
                height: 80vw;
                max-width: 800px;
                max-height: 800px;
                background: radial-gradient(circle, rgba(245,158,11,0.2) 0%, transparent 70%);
                top: -20%;
                right: -10%;
                border-radius: 50%;
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
                text-shadow: 0 10px 30px rgba(0,0,0,0.3);
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

            .kegiatan-photo-placeholder {
                height: 220px;
            }

            .avatar-circle {
                width: 100px;
                height: 100px;
                font-size: 2rem;
                font-weight: 700;
                color: #fff;
                box-shadow: 0 10px 20px -5px rgba(0,0,0,0.2);
            }

            .galeri-item {
                aspect-ratio: 1 / 1;
                overflow: hidden;
                border-radius: 1rem;
                cursor: pointer;
                position: relative;
                box-shadow: 0 10px 20px -5px rgba(0,0,0,0.1);
            }

            .galeri-item-inner {
                width: 100%;
                height: 100%;
                transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .galeri-item:hover .galeri-item-inner {
                transform: scale(1.1);
            }

            .galeri-item-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(to top, rgba(15,23,42,0.8), transparent);
                display: flex;
                align-items: flex-end;
                justify-content: center;
                opacity: 0;
                padding-bottom: 1.5rem;
                transition: all 0.3s ease;
            }

            .galeri-item:hover .galeri-item-overlay {
                opacity: 1;
            }

            /* Lightbox styles */
            .galeri-lightbox { z-index: 2000; }
            .galeri-lightbox-backdrop { z-index: 1; }
            .galeri-lightbox-image {
                z-index: 2;
                max-width: min(90vw, 800px);
                max-height: 85vh;
                border-radius: 1rem;
            }
            .galeri-lightbox-close, .galeri-lightbox-nav {
                z-index: 3;
                pointer-events: auto;
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(8px);
                border: 1px solid rgba(255,255,255,0.2);
            }
            .galeri-lightbox-nav {
                width: 56px; height: 56px;
                border-radius: 50%;
                color: #fff; font-size: 2rem;
                display: flex; align-items: center; justify-content: center;
                transition: all 0.3s ease;
                top: 50%; transform: translateY(-50%);
            }
            .galeri-lightbox-nav:hover, .galeri-lightbox-close:hover {
                background: rgba(255, 255, 255, 0.25);
                transform: translateY(-50%) scale(1.1);
            }
            .galeri-lightbox-close {
                top: 1.5rem; right: 1.5rem;
                width: 48px; height: 48px;
                border-radius: 50%;
                color: #fff; font-size: 2rem;
                transform: none;
            }
            .galeri-lightbox-close:hover {
                transform: scale(1.1);
            }

            .kontak-map {
                border: 0;
                border-radius: 1.25rem;
                width: 100%;
                height: 400px;
                box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
            }
    </style>
