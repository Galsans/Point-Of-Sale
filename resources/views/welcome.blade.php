<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KATALOJI Cafe - Coffee & Comfort Food</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            line-height: 1.6;
            color: #2c2013;
        }

        /* Header & Navigation */
        header {
            position: fixed;
            width: 100%;
            background: rgba(44, 32, 19, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 5%;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: #d4a574;
            font-family: 'Arial', sans-serif;
            letter-spacing: 2px;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: #f5e6d3;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #d4a574;
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            background: linear-gradient(rgba(44, 32, 19, 0.4), rgba(44, 32, 19, 0.6)),
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800"><rect fill="%234a3728" width="1200" height="800"/><circle cx="300" cy="200" r="150" fill="%23654a38" opacity="0.3"/><circle cx="900" cy="600" r="200" fill="%23876c54" opacity="0.2"/><rect x="400" y="300" width="400" height="200" fill="%23d4a574" opacity="0.15" rx="20"/></svg>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }

        .hero-content h1 {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #f5e6d3;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .hero-content p {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            color: #d4a574;
        }

        .cta-button {
            display: inline-block;
            padding: 1rem 2.5rem;
            background: #d4a574;
            color: #2c2013;
            text-decoration: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: bold;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(212, 165, 116, 0.4);
        }

        .cta-button:hover {
            background: #e8b87d;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 165, 116, 0.6);
        }

        /* About Section */
        .about {
            padding: 5rem 5%;
            background: #f5e6d3;
            text-align: center;
        }

        .section-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #2c2013;
        }

        .section-subtitle {
            font-size: 1.2rem;
            color: #654a38;
            margin-bottom: 3rem;
            font-style: italic;
        }

        .about-content {
            max-width: 800px;
            margin: 0 auto;
            font-size: 1.1rem;
            line-height: 1.8;
            color: #4a3728;
        }

        /* Menu Section */
        .menu {
            padding: 5rem 5%;
            background: white;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 3rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .menu-category {
            background: #f5e6d3;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .menu-category:hover {
            transform: translateY(-5px);
        }

        .menu-category h3 {
            font-size: 1.8rem;
            color: #654a38;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #d4a574;
            padding-bottom: 0.5rem;
        }

        .menu-item {
            margin-bottom: 1.5rem;
        }

        .menu-item h4 {
            font-size: 1.2rem;
            color: #2c2013;
            margin-bottom: 0.3rem;
        }

        .menu-item p {
            color: #654a38;
            font-size: 0.95rem;
            font-style: italic;
        }

        .price {
            color: #d4a574;
            font-weight: bold;
            font-size: 1.1rem;
        }

        /* Features Section */
        .features {
            padding: 5rem 5%;
            background: #4a3728;
            color: white;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 3rem auto 0;
        }

        .feature-card {
            text-align: center;
            padding: 2rem;
            background: rgba(212, 165, 116, 0.1);
            border-radius: 15px;
            transition: background 0.3s;
        }

        .feature-card:hover {
            background: rgba(212, 165, 116, 0.2);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #d4a574;
        }

        .feature-card p {
            color: #f5e6d3;
        }

        /* Gallery Section */
        .gallery {
            padding: 5rem 5%;
            background: #f5e6d3;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            max-width: 1200px;
            margin: 3rem auto 0;
        }

        .gallery-item {
            height: 250px;
            background: linear-gradient(135deg, #654a38, #876c54);
            border-radius: 15px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .gallery-item:hover {
            transform: scale(1.05);
        }

        .gallery-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: rgba(255, 255, 255, 0.3);
        }

        /* Contact Section */
        .contact {
            padding: 5rem 5%;
            background: white;
            text-align: center;
        }

        .contact-info {
            max-width: 800px;
            margin: 3rem auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .contact-card {
            padding: 2rem;
            background: #f5e6d3;
            border-radius: 15px;
        }

        .contact-card h3 {
            color: #654a38;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }

        .contact-card p {
            color: #4a3728;
            font-size: 1.1rem;
        }

        /* Footer */
        footer {
            background: #2c2013;
            color: #f5e6d3;
            text-align: center;
            padding: 2rem 5%;
        }

        .social-links {
            margin-bottom: 1rem;
        }

        .social-links a {
            color: #d4a574;
            text-decoration: none;
            margin: 0 1rem;
            font-size: 1.5rem;
            transition: color 0.3s;
        }

        .social-links a:hover {
            color: #e8b87d;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                gap: 1rem;
            }

            .hero-content h1 {
                font-size: 2.5rem;
            }

            .hero-content p {
                font-size: 1.2rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .menu-grid,
            .features-grid,
            .gallery-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <nav>
            <div class="logo">KATALOJI CAFE</div>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#menu">Menu</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1>KATALOJI Cafe</h1>
            <p>Where Great Coffee Meets Comfort Food</p>
            <a href="#menu" class="cta-button">Explore Our Menu</a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <h2 class="section-title">Tentang Kami</h2>
        <p class="section-subtitle">A Perfect Blend of Coffee Culture and Comfort Dining</p>
        <div class="about-content">
            <p>KATALOJI Cafe menghadirkan pengalaman unik yang memadukan kualitas coffee shop premium dengan hidangan
                comfort food yang mengenyangkan. Kami percaya bahwa kopi yang sempurna harus ditemani dengan makanan
                yang luar biasa.</p>
            <br>
            <p>Dengan suasana hangat dan nyaman, kami menciptakan ruang di mana Anda bisa menikmati secangkir kopi
                artisan sambil menikmati hidangan lezat, baik untuk sarapan, makan siang, atau sekadar bersantai bersama
                teman.</p>
        </div>
    </section>

    <!-- Menu Section -->
    <section id="menu" class="menu">
        <h2 class="section-title" style="text-align: center;">Menu Pilihan</h2>
        <p class="section-subtitle" style="text-align: center;">Dipilih dengan penuh perhatian untuk kepuasan Anda</p>

        <div class="menu-grid">
            <!-- Coffee & Beverages -->
            <div class="menu-category">
                <h3>☕ Coffee & Beverages</h3>
                <div class="menu-item">
                    <h4>Espresso <span class="price">Rp 28.000</span></h4>
                    <p>Single/double shot espresso dari beans pilihan</p>
                </div>
                <div class="menu-item">
                    <h4>Cappuccino <span class="price">Rp 35.000</span></h4>
                    <p>Espresso dengan steamed milk & foam sempurna</p>
                </div>
                <div class="menu-item">
                    <h4>Cafe Latte <span class="price">Rp 38.000</span></h4>
                    <p>Smooth & creamy dengan latte art</p>
                </div>
                <div class="menu-item">
                    <h4>Cold Brew <span class="price">Rp 42.000</span></h4>
                    <p>Seduhan dingin 12 jam untuk rasa maksimal</p>
                </div>
            </div>

            <!-- Main Course -->
            <div class="menu-category">
                <h3>🍽️ Main Course</h3>
                <div class="menu-item">
                    <h4>Beef Aglio Olio <span class="price">Rp 55.000</span></h4>
                    <p>Pasta dengan beef slices & garlic oil</p>
                </div>
                <div class="menu-item">
                    <h4>Chicken Steak <span class="price">Rp 58.000</span></h4>
                    <p>Grilled chicken dengan mushroom sauce</p>
                </div>
                <div class="menu-item">
                    <h4>Nasi Goreng Kampung <span class="price">Rp 45.000</span></h4>
                    <p>Nasi goreng special dengan ayam suwir</p>
                </div>
                <div class="menu-item">
                    <h4>Salmon Teriyaki Rice <span class="price">Rp 68.000</span></h4>
                    <p>Salmon teriyaki dengan nasi & sayuran</p>
                </div>
            </div>

            <!-- Snacks & Desserts -->
            <div class="menu-category">
                <h3>🥐 Snacks & Desserts</h3>
                <div class="menu-item">
                    <h4>French Fries <span class="price">Rp 25.000</span></h4>
                    <p>Kentang goreng crispy dengan saus pilihan</p>
                </div>
                <div class="menu-item">
                    <h4>Chicken Wings <span class="price">Rp 38.000</span></h4>
                    <p>6pcs sayap ayam dengan sauce special</p>
                </div>
                <div class="menu-item">
                    <h4>Chocolate Lava Cake <span class="price">Rp 32.000</span></h4>
                    <p>Warm cake dengan molten chocolate</p>
                </div>
                <div class="menu-item">
                    <h4>Tiramisu <span class="price">Rp 35.000</span></h4>
                    <p>Classic Italian dessert dengan coffee</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <h2 class="section-title" style="text-align: center;">Mengapa KATALOJI Cafe?</h2>
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
                <h3>Free High-Speed WiFi</h3>
                <p>Stay connected dengan WiFi cepat dan unlimited untuk semua pengunjung</p>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery">
        <h2 class="section-title" style="text-align: center;">Gallery</h2>
        <p class="section-subtitle" style="text-align: center;">Sekilas suasana & hidangan kami</p>
        <div class="gallery-grid">
            <div class="gallery-item">
                <div class="gallery-placeholder">☕</div>
            </div>
            <div class="gallery-item">
                <div class="gallery-placeholder">🍝</div>
            </div>
            <div class="gallery-item">
                <div class="gallery-placeholder">🥗</div>
            </div>
            <div class="gallery-item">
                <div class="gallery-placeholder">🍰</div>
            </div>
            <div class="gallery-item">
                <div class="gallery-placeholder">🏠</div>
            </div>
            <div class="gallery-item">
                <div class="gallery-placeholder">☕</div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <h2 class="section-title">Kunjungi Kami</h2>
        <p class="section-subtitle">Kami tunggu kehadiran Anda</p>

        <div class="contact-info">
            <div class="contact-card">
                <h3>📍 Alamat</h3>
                <p>Jl. Sudirman No. 123<br>Jakarta Selatan<br>12190</p>
            </div>
            <div class="contact-card">
                <h3>🕒 Jam Operasional</h3>
                <p>Senin - Jumat: 08:00 - 22:00<br>Sabtu - Minggu: 09:00 - 23:00</p>
            </div>
            <div class="contact-card">
                <h3>📞 Kontak</h3>
                <p>Phone: (021) 1234-5678<br>WhatsApp: 0812-3456-7890<br>Email: hello@KATALOJIcafe.com</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="social-links">
            <a href="#">Instagram</a>
            <a href="#">Facebook</a>
            <a href="#">Twitter</a>
        </div>
        <p>&copy; 2024 KATALOJI Cafe. All rights reserved.</p>
    </footer>
</body>

</html>
