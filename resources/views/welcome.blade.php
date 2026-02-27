<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KATALOJI Cafe - Coffee & Comfort Food</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --brown-dark: #2c2013;
            --brown-mid: #4a3728;
            --brown-light: #654a38;
            --gold: #c8a96e;
            --cream: #f5e6d3;
            --white: #fdfaf7;
            --black: #0f0f0f;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            color: var(--brown-dark);
            background: var(--white);
            overflow-x: hidden;
        }

        /* ── HEADER ── */
        header {
            position: fixed;
            width: 100%;
            background: rgba(44, 32, 19, 0.97);
            backdrop-filter: blur(12px);
            padding: 0 5%;
            z-index: 1000;
            height: 64px;
            display: flex;
            align-items: center;
            box-shadow: 0 1px 0 rgba(200, 169, 110, 0.15);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: var(--gold);
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: rgba(245, 230, 211, 0.8);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 400;
            letter-spacing: 0.5px;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: var(--gold);
        }

        /* Hamburger */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 4px;
        }

        .hamburger span {
            display: block;
            width: 24px;
            height: 2px;
            background: var(--cream);
            transition: all 0.3s;
            border-radius: 2px;
        }

        .hamburger.open span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .hamburger.open span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.open span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        /* Mobile Nav */
        .mobile-nav {
            display: none;
            position: fixed;
            top: 64px;
            left: 0;
            right: 0;
            background: rgba(44, 32, 19, 0.98);
            padding: 1.5rem 5%;
            z-index: 999;
            flex-direction: column;
            gap: 1.2rem;
            border-top: 1px solid rgba(200, 169, 110, 0.2);
        }

        .mobile-nav.open {
            display: flex;
        }

        .mobile-nav a {
            color: var(--cream);
            text-decoration: none;
            font-size: 1rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }

        .mobile-nav a:hover {
            color: var(--gold);
        }

        /* ── HERO ── */
        .hero {
            height: 100vh;
            min-height: 600px;
            background:
                linear-gradient(to bottom, rgba(44, 32, 19, 0.5) 0%, rgba(44, 32, 19, 0.75) 100%),
                url('https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=1600&q=80') center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 0 5%;
        }

        .hero-content {
            max-width: 700px;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(200, 169, 110, 0.2);
            border: 1px solid rgba(200, 169, 110, 0.4);
            color: var(--gold);
            font-size: 0.75rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            padding: 6px 18px;
            border-radius: 50px;
            margin-bottom: 1.5rem;
        }

        .hero-content h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.8rem, 7vw, 5rem);
            color: var(--cream);
            line-height: 1.1;
            margin-bottom: 1rem;
        }

        .hero-content p {
            font-size: clamp(1rem, 2.5vw, 1.25rem);
            color: rgba(245, 230, 211, 0.8);
            margin-bottom: 2.5rem;
            font-weight: 300;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            display: inline-block;
            padding: 0.9rem 2.2rem;
            background: var(--gold);
            color: var(--brown-dark);
            text-decoration: none;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(200, 169, 110, 0.4);
        }

        .btn-primary:hover {
            background: #d4b87a;
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(200, 169, 110, 0.5);
        }

        .btn-outline {
            display: inline-block;
            padding: 0.9rem 2.2rem;
            border: 1.5px solid rgba(245, 230, 211, 0.5);
            color: var(--cream);
            text-decoration: none;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 400;
            transition: all 0.3s;
        }

        .btn-outline:hover {
            border-color: var(--cream);
            background: rgba(245, 230, 211, 0.1);
        }

        /* ── ABOUT ── */
        .about {
            padding: 6rem 5%;
            background: var(--cream);
        }

        .about-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
        }

        .about-text .label {
            font-size: 0.72rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--gold);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .about-text h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 4vw, 2.8rem);
            color: var(--brown-dark);
            line-height: 1.25;
            margin-bottom: 1.5rem;
        }

        .about-text p {
            color: var(--brown-light);
            line-height: 1.8;
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }

        .about-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .stat-item {
            background: white;
            padding: 1.2rem 1.5rem;
            border-radius: 12px;
            border-left: 3px solid var(--gold);
        }

        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: var(--brown-dark);
            font-weight: 700;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--brown-light);
            margin-top: 2px;
        }

        .about-image {
            position: relative;
        }

        .about-img-main {
            width: 100%;
            aspect-ratio: 4/5;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(44, 32, 19, 0.2);
        }

        .about-img-accent {
            position: absolute;
            bottom: -20px;
            left: -20px;
            width: 55%;
            aspect-ratio: 1;
            object-fit: cover;
            border-radius: 16px;
            border: 4px solid var(--cream);
            box-shadow: 0 10px 30px rgba(44, 32, 19, 0.15);
        }

        /* ── MENU ── */
        .menu {
            padding: 6rem 5%;
            background: var(--white);
        }

        .section-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 3.5rem;
        }

        .section-header .label {
            font-size: 0.72rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--gold);
            font-weight: 600;
            margin-bottom: 0.8rem;
        }

        .section-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            color: var(--brown-dark);
            margin-bottom: 0.8rem;
        }

        .section-header p {
            color: var(--brown-light);
            font-size: 0.95rem;
            font-style: italic;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .menu-category {
            background: var(--cream);
            padding: 2rem;
            border-radius: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid rgba(200, 169, 110, 0.15);
        }

        .menu-category:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(44, 32, 19, 0.12);
        }

        .menu-category h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            color: var(--brown-dark);
            margin-bottom: 1.5rem;
            padding-bottom: 0.8rem;
            border-bottom: 2px solid var(--gold);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .menu-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0.75rem 0;
            border-bottom: 1px dashed rgba(100, 74, 56, 0.15);
            gap: 1rem;
        }

        .menu-item:last-child {
            border-bottom: none;
        }

        .menu-item-info h4 {
            font-size: 0.92rem;
            font-weight: 500;
            color: var(--brown-dark);
            margin-bottom: 2px;
        }

        .menu-item-info p {
            font-size: 0.78rem;
            color: var(--brown-light);
            font-style: italic;
            line-height: 1.4;
        }

        .price {
            color: var(--gold);
            font-weight: 600;
            font-size: 0.88rem;
            white-space: nowrap;
            flex-shrink: 0;
        }

        /* ── FEATURES ── */
        .features {
            padding: 6rem 5%;
            background: var(--brown-dark);
            color: white;
        }

        .features .section-header .label {
            color: var(--gold);
        }

        .features .section-header h2 {
            color: var(--cream);
        }

        .features .section-header p {
            color: rgba(245, 230, 211, 0.6);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 1.5rem;
            max-width: 1100px;
            margin: 0 auto;
        }

        .feature-card {
            padding: 2rem 1.5rem;
            background: rgba(200, 169, 110, 0.07);
            border: 1px solid rgba(200, 169, 110, 0.15);
            border-radius: 16px;
            transition: background 0.3s, transform 0.3s;
            text-align: center;
        }

        .feature-card:hover {
            background: rgba(200, 169, 110, 0.14);
            transform: translateY(-4px);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem;
            color: var(--gold);
            margin-bottom: 0.5rem;
        }

        .feature-card p {
            color: rgba(245, 230, 211, 0.7);
            font-size: 0.88rem;
            line-height: 1.6;
        }

        /* ── GALLERY ── */
        .gallery {
            padding: 6rem 5%;
            background: var(--cream);
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-template-rows: 280px 280px;
            gap: 12px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .gallery-item {
            overflow: hidden;
            border-radius: 14px;
            cursor: pointer;
            position: relative;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
            display: block;
        }

        .gallery-item:hover img {
            transform: scale(1.07);
        }

        .gallery-item:nth-child(1) {
            grid-column: span 5;
        }

        .gallery-item:nth-child(2) {
            grid-column: span 4;
        }

        .gallery-item:nth-child(3) {
            grid-column: span 3;
        }

        .gallery-item:nth-child(4) {
            grid-column: span 3;
        }

        .gallery-item:nth-child(5) {
            grid-column: span 4;
        }

        .gallery-item:nth-child(6) {
            grid-column: span 5;
        }

        .gallery-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(44, 32, 19, 0.6) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.3s;
            display: flex;
            align-items: flex-end;
            padding: 1rem;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-overlay span {
            color: white;
            font-size: 0.82rem;
            letter-spacing: 0.5px;
        }

        /* ── CONTACT ── */
        .contact {
            padding: 6rem 5%;
            background: var(--black);
            color: #fff;
        }

        .contact-header {
            text-align: center;
            margin-bottom: 3.5rem;
        }

        .contact-header .label {
            font-size: 0.72rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--gold);
            font-weight: 600;
            margin-bottom: 0.8rem;
        }

        .contact-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            color: #fff;
            margin-bottom: 0.5rem;
        }

        .contact-header p {
            color: #999;
            font-size: 0.95rem;
        }

        .contact-wrapper {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 2rem;
            max-width: 1100px;
            margin: 0 auto;
            align-items: stretch;
        }

        .contact-cards {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .contact-card {
            background: #161616;
            border: 1px solid #222;
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            transition: border-color 0.2s, transform 0.2s;
        }

        .contact-card:hover {
            border-color: var(--gold);
            transform: translateY(-2px);
        }

        .contact-card-icon {
            font-size: 1.4rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .contact-card-content h3 {
            font-size: 0.68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--gold);
            margin: 0 0 6px;
        }

        .contact-card-content p {
            color: #bbb;
            font-size: 0.85rem;
            line-height: 1.6;
            margin: 0;
        }

        .contact-card-link {
            display: inline-block;
            margin-top: 8px;
            color: var(--gold);
            font-size: 0.78rem;
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .contact-card-link:hover {
            opacity: 0.7;
        }

        .hours-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3px 12px;
            font-size: 0.83rem;
            color: #bbb;
        }

        .hours-grid span:nth-child(even) {
            color: #fff;
            font-weight: 500;
        }

        .contact-links {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .contact-links a {
            color: #bbb;
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.2s;
        }

        .contact-links a:hover {
            color: var(--gold);
        }

        .contact-map {
            border-radius: 16px;
            overflow: hidden;
            min-height: 400px;
            border: 1px solid #222;
        }

        .contact-map iframe {
            display: block;
            width: 100%;
            height: 100%;
            min-height: 400px;
            filter: grayscale(20%) contrast(1.05);
        }

        /* ── FOOTER ── */
        footer {
            background: #080808;
            padding: 2.5rem 5%;
            text-align: center;
            border-top: 1px solid #1a1a1a;
        }

        .footer-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            color: var(--gold);
            letter-spacing: 3px;
            margin-bottom: 0.5rem;
        }

        footer p {
            color: #555;
            font-size: 0.82rem;
        }

        /* ── SCROLL TO TOP ── */
        .scroll-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 44px;
            height: 44px;
            background: var(--gold);
            color: var(--brown-dark);
            border: none;
            border-radius: 50%;
            font-size: 1.1rem;
            cursor: pointer;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s, transform 0.3s;
            z-index: 900;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .scroll-top.visible {
            opacity: 1;
            pointer-events: auto;
        }

        .scroll-top:hover {
            transform: translateY(-3px);
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 992px) {
            .about-inner {
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            .about-image {
                display: none;
            }

            .gallery-grid {
                grid-template-columns: repeat(6, 1fr);
                grid-template-rows: 220px 220px;
            }

            .gallery-item:nth-child(1) {
                grid-column: span 3;
            }

            .gallery-item:nth-child(2) {
                grid-column: span 3;
            }

            .gallery-item:nth-child(3) {
                grid-column: span 2;
            }

            .gallery-item:nth-child(4) {
                grid-column: span 2;
            }

            .gallery-item:nth-child(5) {
                grid-column: span 2;
            }

            .gallery-item:nth-child(6) {
                grid-column: span 6;
            }

            .contact-wrapper {
                grid-template-columns: 1fr;
            }

            .contact-map {
                min-height: 300px;
            }

            .contact-map iframe {
                min-height: 300px;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hamburger {
                display: flex;
            }

            .about,
            .menu,
            .features,
            .gallery,
            .contact {
                padding: 4rem 5%;
            }

            .about-stats {
                grid-template-columns: 1fr 1fr;
            }

            .menu-grid {
                grid-template-columns: 1fr;
            }

            .gallery-grid {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: auto;
            }

            .gallery-item {
                height: 180px;
            }

            .gallery-item:nth-child(n) {
                grid-column: span 1;
            }

            .gallery-item:nth-child(6) {
                grid-column: span 2;
            }

            .features-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 480px) {
            .hero-content h1 {
                font-size: 2.4rem;
            }

            .hero-actions {
                flex-direction: column;
                align-items: center;
            }

            .btn-primary,
            .btn-outline {
                width: 100%;
                text-align: center;
            }

            .about-stats {
                grid-template-columns: 1fr;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .gallery-grid {
                grid-template-columns: 1fr;
            }

            .gallery-item:nth-child(n) {
                grid-column: span 1;
            }

            .gallery-item {
                height: 200px;
            }
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header>
        <nav>
            <div class="logo">KATALOJI</div>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#menu">Menu</a></li>
                <li><a href="#gallery">Gallery</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <div class="hamburger" id="hamburger" onclick="toggleMenu()">
                <span></span><span></span><span></span>
            </div>
        </nav>
    </header>

    <!-- Mobile Nav -->
    <div class="mobile-nav" id="mobileNav">
        <a href="#home" onclick="closeMenu()">Home</a>
        <a href="#about" onclick="closeMenu()">About</a>
        <a href="#menu" onclick="closeMenu()">Menu</a>
        <a href="#gallery" onclick="closeMenu()">Gallery</a>
        <a href="#contact" onclick="closeMenu()">Contact</a>
    </div>

    <!-- Hero -->
    <section id="home" class="hero">
        <div class="hero-content">
            <div class="hero-badge">Est. 2020 · Jakarta Timur</div>
            <h1>KATALOJI Cafe</h1>
            <p>Where Great Coffee Meets Comfort Food</p>
            <div class="hero-actions">
                <a href="#menu" class="btn-primary">Lihat Menu</a>
                <a href="#contact" class="btn-outline">Kunjungi Kami</a>
            </div>
        </div>
    </section>

    <!-- About -->
    <section id="about" class="about">
        <div class="about-inner">
            <div class="about-text">
                <div class="label">Tentang Kami</div>
                <h2>A Perfect Blend of Coffee & Comfort</h2>
                <p>KATALOJI Cafe menghadirkan pengalaman unik yang memadukan kualitas coffee shop premium dengan
                    hidangan comfort food yang mengenyangkan. Kami percaya bahwa kopi yang sempurna harus ditemani
                    dengan makanan yang luar biasa.</p>
                <p>Dengan suasana hangat dan nyaman, kami menciptakan ruang di mana Anda bisa menikmati secangkir kopi
                    artisan sambil menikmati hidangan lezat, baik untuk sarapan, makan siang, atau sekadar bersantai
                    bersama teman.</p>
                <div class="about-stats">
                    <div class="stat-item">
                        <div class="stat-num">5+</div>
                        <div class="stat-label">Tahun Beroperasi</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num">50+</div>
                        <div class="stat-label">Item Menu</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num">10K+</div>
                        <div class="stat-label">Pelanggan Puas</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num">4.8★</div>
                        <div class="stat-label">Rating Google</div>
                    </div>
                </div>
            </div>
            <div class="about-image">
                <img class="about-img-main" src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=800&q=80"
                    alt="Interior Kataloji Cafe">
                <img class="about-img-accent"
                    src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=400&q=80" alt="Coffee">
            </div>
        </div>
    </section>

    <!-- Menu -->
    <section id="menu" class="menu">
        <div class="section-header">
            <div class="label">Menu Kami</div>
            <h2>Menu Pilihan</h2>
            <p>Dipilih dengan penuh perhatian untuk kepuasan Anda</p>
        </div>

        <div class="menu-grid">
            @forelse ($menuCategories->take(6) as $category)
                <div class="menu-category">
                    <h3>{{ $category->icon ?? '🍽️' }} {{ $category->name }}</h3>

                    {{-- Menu langsung di parent --}}
                    @foreach ($category->menus as $menu)
                        <div class="menu-item">
                            <div class="menu-item-info">
                                <h4>{{ $menu->name }}</h4>
                                @if ($menu->description)
                                    <p>{{ $menu->description }}</p>
                                @endif
                            </div>
                            <span class="price">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                        </div>
                    @endforeach

                    {{-- Menu dari child categories --}}
                    @foreach ($category->children as $child)
                        @if ($child->menus->count() > 0)
                            <div style="margin: 10px 0 4px;">
                                <small
                                    style="
                                color: var(--gold);
                                font-size: 0.72rem;
                                text-transform: uppercase;
                                letter-spacing: 1.5px;
                                font-weight: 600;
                            ">—
                                    {{ $child->name }}</small>
                            </div>

                            @foreach ($child->menus as $menu)
                                <div class="menu-item">
                                    <div class="menu-item-info">
                                        <h4>{{ $menu->name }}</h4>
                                        @if ($menu->description)
                                            <p>{{ $menu->description }}</p>
                                        @endif
                                    </div>
                                    <span class="price">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Jika parent & semua child tidak punya menu --}}
                    @if ($category->menus->isEmpty() && $category->children->every(fn($c) => $c->menus->isEmpty()))
                        <p style="color:#999;font-size:0.85rem;font-style:italic;">
                            Belum ada menu tersedia
                        </p>
                    @endif
                </div>

            @empty
                {{-- Fallback jika DB kosong --}}
                <div class="menu-category">
                    <h3>☕ Coffee & Beverages</h3>
                    <div class="menu-item">
                        <div class="menu-item-info">
                            <h4>Espresso</h4>
                            <p>Single/double shot espresso dari beans pilihan</p>
                        </div>
                        <span class="price">Rp 28.000</span>
                    </div>
                    <div class="menu-item">
                        <div class="menu-item-info">
                            <h4>Cappuccino</h4>
                            <p>Espresso dengan steamed milk & foam sempurna</p>
                        </div>
                        <span class="price">Rp 35.000</span>
                    </div>
                    <div class="menu-item">
                        <div class="menu-item-info">
                            <h4>Cold Brew</h4>
                            <p>Seduhan dingin 12 jam untuk rasa maksimal</p>
                        </div>
                        <span class="price">Rp 42.000</span>
                    </div>
                </div>
                <div class="menu-category">
                    <h3>🍽️ Main Course</h3>
                    <div class="menu-item">
                        <div class="menu-item-info">
                            <h4>Beef Aglio Olio</h4>
                            <p>Pasta dengan beef slices & garlic oil</p>
                        </div>
                        <span class="price">Rp 55.000</span>
                    </div>
                    <div class="menu-item">
                        <div class="menu-item-info">
                            <h4>Chicken Steak</h4>
                            <p>Grilled chicken dengan mushroom sauce</p>
                        </div>
                        <span class="price">Rp 58.000</span>
                    </div>
                    <div class="menu-item">
                        <div class="menu-item-info">
                            <h4>Salmon Teriyaki Rice</h4>
                            <p>Salmon teriyaki dengan nasi & sayuran</p>
                        </div>
                        <span class="price">Rp 68.000</span>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Features -->
    <section class="features">
        <div class="section-header">
            <div class="label">Keunggulan</div>
            <h2>Mengapa KATALOJI Cafe?</h2>
            <p>Lebih dari sekadar tempat makan dan minum</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">☕</div>
                <h3>Premium Coffee</h3>
                <p>Beans berkualitas tinggi dari berbagai region, diseduh oleh barista berpengalaman</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🍳</div>
                <h3>Fresh Ingredients</h3>
                <p>Semua hidangan dibuat dari bahan segar pilihan setiap hari</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🏠</div>
                <h3>Cozy Atmosphere</h3>
                <p>Interior hangat dan nyaman, perfect untuk kerja, belajar, atau hangout</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📶</div>
                <h3>Free WiFi</h3>
                <p>Stay connected dengan WiFi cepat dan unlimited untuk semua pengunjung</p>
            </div>
        </div>
    </section>

    <!-- Gallery -->
    <section id="gallery" class="gallery">
        <div class="section-header">
            <div class="label">Gallery</div>
            <h2>Sekilas Suasana Kami</h2>
            <p>Tempat yang nyaman untuk semua momen</p>
        </div>
        <div class="gallery-grid">
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=800&q=80"
                    alt="Cafe Interior">
                <div class="gallery-overlay"><span>Interior Cafe</span></div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=600&q=80" alt="Coffee">
                <div class="gallery-overlay"><span>Premium Coffee</span></div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=500&q=80" alt="Food">
                <div class="gallery-overlay"><span>Comfort Food</span></div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1551024601-bec78aea704b?w=500&q=80" alt="Dessert">
                <div class="gallery-overlay"><span>Desserts</span></div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1445116572660-236099ec97a0?w=600&q=80" alt="Cafe Vibes">
                <div class="gallery-overlay"><span>Cafe Vibes</span></div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1453614512568-c4024d13c247?w=800&q=80" alt="Latte Art">
                <div class="gallery-overlay"><span>Latte Art</span></div>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section id="contact" class="contact">
        <div class="contact-header">
            <div class="label">Temukan Kami</div>
            <h2>Kunjungi Kami</h2>
            <p>Kami tunggu kehadiran Anda</p>
        </div>

        <div class="contact-wrapper">
            <div class="contact-cards">
                <div class="contact-card">
                    <div class="contact-card-icon">📍</div>
                    <div class="contact-card-content">
                        <h3>Alamat</h3>
                        <p>Ruko Puri Cipayung, Jl. Setu Cipayung No.09,<br>Cipayung, Jakarta Timur 13840</p>
                        <a href="https://maps.app.goo.gl/sjTYXt3FQTLQEUtt9" target="_blank"
                            class="contact-card-link">
                            Buka di Google Maps →
                        </a>
                    </div>
                </div>

                <div class="contact-card">
                    <div class="contact-card-icon">🕒</div>
                    <div class="contact-card-content">
                        <h3>Jam Operasional</h3>
                        <div class="hours-grid">
                            <span>Senin – Jumat</span><span>10:00 – 22:00</span>
                            <span>Sabtu – Minggu</span><span>09:00 – 23:00</span>
                        </div>
                    </div>
                </div>

                <div class="contact-card">
                    <div class="contact-card-icon">📞</div>
                    <div class="contact-card-content">
                        <h3>Kontak</h3>
                        <div class="contact-links">
                            <a href="tel:02112345678">📱 (021) 1234-5678</a>
                            <a href="https://wa.me/6281234567890" target="_blank">💬 WhatsApp</a>
                            <a href="mailto:hello@katalojicafe.com">✉️ hello@katalojicafe.com</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="contact-map">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3573.1019354744603!2d106.9037941!3d-6.326131599999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69ed5730e887c7%3A0xbb7845b7ac0628cb!2sKataloji%20Coffee%20and%20Eatery!5e1!3m2!1sid!2sid!4v1772177645808!5m2!1sid!2sid"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-logo">KATALOJI CAFE</div>
        <p>&copy; 2025 KATALOJI Cafe. All rights reserved.</p>
    </footer>

    <!-- Scroll to Top -->
    <button class="scroll-top" id="scrollTop" onclick="window.scrollTo({top:0,behavior:'smooth'})">↑</button>

    <script>
        // Hamburger menu
        function toggleMenu() {
            const nav = document.getElementById('mobileNav');
            const ham = document.getElementById('hamburger');
            nav.classList.toggle('open');
            ham.classList.toggle('open');
        }

        function closeMenu() {
            document.getElementById('mobileNav').classList.remove('open');
            document.getElementById('hamburger').classList.remove('open');
        }

        // Scroll to top button
        window.addEventListener('scroll', () => {
            const btn = document.getElementById('scrollTop');
            btn.classList.toggle('visible', window.scrollY > 400);
        });

        // Close mobile menu on outside click
        document.addEventListener('click', (e) => {
            const nav = document.getElementById('mobileNav');
            const ham = document.getElementById('hamburger');
            if (nav.classList.contains('open') && !nav.contains(e.target) && !ham.contains(e.target)) {
                closeMenu();
            }
        });
    </script>

</body>

</html>
