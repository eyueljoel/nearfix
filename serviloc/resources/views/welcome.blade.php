<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ServiLoc — Local Service Marketplace in Ethiopia</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --blue:       #0ea5e9;
            --blue-dark:  #2563eb;
            --blue-light: #eff6ff;
            --white:      #ffffff;
            --bg:         #f8fafc;
            --border:     #e2e8f0;
            --text:       #0f172a;
            --muted:      #64748b;
            --green:      #059669;
            --radius:     12px;
            --radius-lg:  20px;
        }

        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: var(--white);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
        }

        /* ── NAV ──────────────────────────────────────── */
        .nav {
            position: sticky; top: 0; z-index: 100;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 0 5%;
            height: 64px;
            display: flex; align-items: center; justify-content: space-between;
        }

        .nav-brand {
            display: flex; align-items: center; gap: 9px;
            font-size: 20px; font-weight: 800; color: var(--text);
            text-decoration: none; letter-spacing: -0.3px;
        }

        .nav-brand .icon {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--blue), var(--blue-dark));
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 17px;
        }

        .nav-brand span { color: var(--blue); }

        .nav-links { display: flex; align-items: center; gap: 6px; }

        .nav-link {
            padding: 8px 16px; border-radius: 8px;
            font-size: 14px; font-weight: 500; color: var(--muted);
            text-decoration: none; transition: all 0.18s;
        }

        .nav-link:hover { background: var(--blue-light); color: var(--blue-dark); }

        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 20px; border-radius: var(--radius);
            font-size: 14px; font-weight: 600;
            text-decoration: none; cursor: pointer; border: none;
            font-family: inherit; transition: all 0.2s;
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--blue); color: white;
        }

        .btn-primary:hover {
            background: var(--blue-dark);
            box-shadow: 0 4px 16px rgba(14,165,233,0.4);
            transform: translateY(-1px);
        }

        .btn-outline {
            background: white; color: var(--blue-dark);
            border: 1.5px solid var(--blue);
        }

        .btn-outline:hover {
            background: var(--blue-light);
            transform: translateY(-1px);
        }

        .btn-lg { padding: 13px 28px; font-size: 15px; border-radius: 14px; }
        .btn-white { background: white; color: var(--blue-dark); }
        .btn-white:hover { background: var(--blue-light); transform: translateY(-1px); }
        .btn-ghost-white {
            background: rgba(255,255,255,0.12); color: white;
            border: 1.5px solid rgba(255,255,255,0.3);
        }
        .btn-ghost-white:hover { background: rgba(255,255,255,0.22); transform: translateY(-1px); }

        /* ── HERO ─────────────────────────────────────── */
        .hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 45%, #0c4a6e 75%, #0e7490 100%);
            padding: 100px 5% 80px;
            position: relative; overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .hero-inner {
            max-width: 780px; position: relative; z-index: 1;
        }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 7px;
            background: rgba(14,165,233,0.15);
            border: 1px solid rgba(14,165,233,0.3);
            color: #7dd3fc; padding: 6px 14px; border-radius: 50px;
            font-size: 13px; font-weight: 500; margin-bottom: 24px;
        }

        .hero-badge span { width: 6px; height: 6px; background: #0ea5e9; border-radius: 50%; display: inline-block; }

        .hero h1 {
            font-size: clamp(36px, 5vw, 60px);
            font-weight: 900; color: white;
            line-height: 1.1; letter-spacing: -1.5px;
            margin-bottom: 20px;
        }

        .hero h1 .highlight {
            background: linear-gradient(90deg, #38bdf8, #818cf8);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 18px; color: rgba(255,255,255,0.7);
            line-height: 1.65; margin-bottom: 36px; max-width: 560px;
        }

        .hero-cta { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 56px; }

        .hero-stats {
            display: flex; gap: 40px; flex-wrap: wrap;
            padding-top: 40px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .hero-stat .value {
            font-size: 30px; font-weight: 800; color: white;
            letter-spacing: -0.5px;
        }

        .hero-stat .label {
            font-size: 13px; color: rgba(255,255,255,0.55); margin-top: 2px;
        }

        /* ── SECTION COMMONS ──────────────────────────── */
        .section { padding: 88px 5%; }
        .section-alt { background: var(--bg); }

        .section-tag {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--blue-light); color: var(--blue-dark);
            padding: 5px 12px; border-radius: 50px;
            font-size: 12px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.6px;
            margin-bottom: 14px;
        }

        .section-title {
            font-size: clamp(26px, 3vw, 38px);
            font-weight: 800; color: var(--text);
            letter-spacing: -0.5px; line-height: 1.2;
            margin-bottom: 14px;
        }

        .section-sub {
            font-size: 16px; color: var(--muted);
            max-width: 520px; line-height: 1.65; margin-bottom: 52px;
        }

        /* ── HOW IT WORKS ─────────────────────────────── */
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
            position: relative;
        }

        .steps-grid::before {
            content: '';
            position: absolute;
            top: 28px; left: 12.5%; right: 12.5%; height: 2px;
            background: linear-gradient(90deg, var(--blue) 0%, var(--blue-dark) 100%);
            z-index: 0;
        }

        .step-card {
            text-align: center; padding: 0 20px;
            position: relative; z-index: 1;
        }

        .step-num {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, var(--blue), var(--blue-dark));
            border-radius: 50%; color: white;
            font-size: 20px; font-weight: 800;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px; box-shadow: 0 4px 16px rgba(14,165,233,0.4);
            border: 4px solid white;
        }

        .step-card h3 { font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 8px; }
        .step-card p  { font-size: 13px; color: var(--muted); line-height: 1.55; }

        /* ── CATEGORIES ───────────────────────────────── */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 14px;
        }

        .category-card {
            background: white;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px 16px;
            text-align: center; text-decoration: none;
            transition: all 0.22s; cursor: pointer;
        }

        .category-card:hover {
            border-color: var(--blue);
            background: var(--blue-light);
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(14,165,233,0.15);
        }

        .category-icon {
            font-size: 36px; margin-bottom: 10px; display: block;
        }

        .category-name {
            font-size: 13.5px; font-weight: 600; color: var(--text);
        }

        .category-count {
            font-size: 12px; color: var(--muted); margin-top: 3px;
        }

        /* ── FEATURES ─────────────────────────────────── */
        .features-grid {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;
        }

        .feature-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 28px 22px;
            transition: all 0.22s;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.07);
            border-color: var(--blue);
        }

        .feature-icon-wrap {
            width: 52px; height: 52px;
            background: var(--blue-light);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; margin-bottom: 18px;
        }

        .feature-card h3 { font-size: 15px; font-weight: 700; margin-bottom: 8px; }
        .feature-card p  { font-size: 13px; color: var(--muted); line-height: 1.6; }

        /* ── TESTIMONIALS ─────────────────────────────── */
        .testimonials-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;
        }

        .testimonial-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 28px;
            transition: all 0.22s;
        }

        .testimonial-card:hover {
            box-shadow: 0 8px 32px rgba(0,0,0,0.07);
            transform: translateY(-3px);
        }

        .stars { color: #f59e0b; font-size: 16px; letter-spacing: 1px; margin-bottom: 14px; }

        .testimonial-text {
            font-size: 14.5px; color: var(--text);
            line-height: 1.7; margin-bottom: 20px;
            font-style: italic;
        }

        .testimonial-author {
            display: flex; align-items: center; gap: 12px;
        }

        .author-avatar {
            width: 42px; height: 42px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 15px; color: white;
            flex-shrink: 0;
        }

        .author-name  { font-size: 14px; font-weight: 700; color: var(--text); }
        .author-role  { font-size: 12px; color: var(--muted); margin-top: 1px; }

        /* ── CTA BANNER ───────────────────────────────── */
        .cta-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0c4a6e 100%);
            border-radius: 24px; padding: 64px 56px;
            display: flex; align-items: center; justify-content: space-between;
            gap: 40px; flex-wrap: wrap; position: relative; overflow: hidden;
        }

        .cta-banner::before {
            content: ''; position: absolute; top: -60px; right: -60px;
            width: 240px; height: 240px;
            background: radial-gradient(circle, rgba(14,165,233,0.25), transparent 70%);
        }

        .cta-banner::after {
            content: ''; position: absolute; bottom: -40px; left: 20%;
            width: 180px; height: 180px;
            background: radial-gradient(circle, rgba(99,102,241,0.2), transparent 70%);
        }

        .cta-text { position: relative; z-index: 1; }
        .cta-text h2 { font-size: 32px; font-weight: 800; color: white; margin-bottom: 10px; letter-spacing: -0.5px; }
        .cta-text p  { font-size: 16px; color: rgba(255,255,255,0.65); }

        .cta-actions { display: flex; gap: 12px; flex-wrap: wrap; position: relative; z-index: 1; }

        /* ── FOOTER ───────────────────────────────────── */
        footer {
            background: #0f172a;
            padding: 56px 5% 32px;
            color: rgba(255,255,255,0.55);
        }

        .footer-top {
            display: flex; justify-content: space-between;
            gap: 48px; flex-wrap: wrap;
            margin-bottom: 40px;
            padding-bottom: 40px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .footer-brand { max-width: 280px; }
        .footer-brand .logo { display: flex; align-items: center; gap: 9px; text-decoration: none; margin-bottom: 14px; }
        .footer-brand .logo .icon { width: 30px; height: 30px; background: linear-gradient(135deg, var(--blue), var(--blue-dark)); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; }
        .footer-brand .logo-name { font-size: 18px; font-weight: 800; color: white; }
        .footer-brand .logo-name span { color: var(--blue); }
        .footer-brand p { font-size: 13px; line-height: 1.6; }

        .footer-col h4 { font-size: 13px; font-weight: 700; color: white; margin-bottom: 14px; text-transform: uppercase; letter-spacing: 0.6px; }
        .footer-col a  { display: block; font-size: 13px; color: rgba(255,255,255,0.5); text-decoration: none; margin-bottom: 9px; transition: color 0.2s; }
        .footer-col a:hover { color: var(--blue); }

        .footer-bottom {
            display: flex; justify-content: space-between; align-items: center;
            font-size: 12px; flex-wrap: wrap; gap: 12px;
        }

        /* ── RESPONSIVE ───────────────────────────────── */
        @media (max-width: 1024px) {
            .features-grid  { grid-template-columns: repeat(2, 1fr); }
            .steps-grid     { grid-template-columns: repeat(2, 1fr); }
            .steps-grid::before { display: none; }
            .testimonials-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .section { padding: 60px 5%; }
            .features-grid  { grid-template-columns: 1fr; }
            .steps-grid     { grid-template-columns: 1fr 1fr; }
            .testimonials-grid { grid-template-columns: 1fr; }
            .cta-banner { padding: 40px 28px; }
            .cta-text h2 { font-size: 24px; }
            .hero { padding: 64px 5% 56px; }
        }

        @media (max-width: 480px) {
            .steps-grid { grid-template-columns: 1fr; }
            .nav-links .nav-link { display: none; }
        }
    </style>
</head>
<body>

<!-- ── NAVBAR ────────────────────────────────────────── -->
<header class="nav">
    <a href="/" class="nav-brand">
        <div class="icon">🛠️</div>
        Servi<span>Loc</span>
    </a>
    <nav class="nav-links">
        <a href="#how-it-works" class="nav-link">How it works</a>
        <a href="#services" class="nav-link">Services</a>
        <a href="#why-us" class="nav-link">Why us</a>
        @auth
            <a href="{{ url('/dashboard') }}" class="btn btn-outline" style="padding:8px 18px;">Dashboard →</a>
        @else
            <a href="{{ route('login') }}" class="nav-link">Sign In</a>
            <a href="{{ route('register') }}" class="btn btn-primary" style="padding:8px 18px;">Get Started</a>
        @endauth
    </nav>
</header>

<!-- ── HERO ──────────────────────────────────────────── -->
<section class="hero">
    <div class="hero-inner">
        <div class="hero-badge">
            <span></span>
            Ethiopia's #1 Local Service Marketplace
        </div>

        <h1>Get the Best Services<br>in <span class="highlight">Ethiopia</span></h1>

        <p>Connect with trusted, verified service providers for any job you need done. From plumbing to tutoring — find the right professional near you.</p>

        <div class="hero-cta">
            @auth
                <a href="{{ route('customer.requests') }}" class="btn btn-white btn-lg">📝 Post a Request</a>
                <a href="{{ route('search') }}" class="btn btn-ghost-white btn-lg">🔍 Browse Services</a>
            @else
                <a href="{{ route('register') }}" class="btn btn-white btn-lg">📝 Post a Request</a>
                <a href="{{ route('login') }}" class="btn btn-ghost-white btn-lg">🔍 Find a Service</a>
            @endauth
        </div>

        <div class="hero-stats">
            <div class="hero-stat">
                <div class="value">500+</div>
                <div class="label">Service Requests</div>
            </div>
            <div class="hero-stat">
                <div class="value">100+</div>
                <div class="label">Verified Providers</div>
            </div>
            <div class="hero-stat">
                <div class="value">98%</div>
                <div class="label">Satisfaction Rate</div>
            </div>
            <div class="hero-stat">
                <div class="value">10+</div>
                <div class="label">Service Categories</div>
            </div>
        </div>
    </div>
</section>

<!-- ── HOW IT WORKS ───────────────────────────────────── -->
<section class="section" id="how-it-works">
    <div style="text-align:center; margin-bottom: 56px;">
        <div class="section-tag">✦ Simple Process</div>
        <h2 class="section-title">How ServiLoc Works</h2>
        <p class="section-sub" style="margin: 0 auto;">Four simple steps to get any service done — from posting your request to completion.</p>
    </div>

    <div class="steps-grid">
        <div class="step-card">
            <div class="step-num">1</div>
            <h3>Post a Request</h3>
            <p>Describe what you need, set your budget and location. It takes under 2 minutes.</p>
        </div>
        <div class="step-card">
            <div class="step-num">2</div>
            <h3>Get Offers</h3>
            <p>Verified providers send you competitive offers with their prices and details.</p>
        </div>
        <div class="step-card">
            <div class="step-num">3</div>
            <h3>Choose a Provider</h3>
            <p>Compare offers, check reviews and portfolios, then accept the best one.</p>
        </div>
        <div class="step-card">
            <div class="step-num">4</div>
            <h3>Service Done ✓</h3>
            <p>Payment is held securely and released only after you confirm it's complete.</p>
        </div>
    </div>
</section>

<!-- ── POPULAR SERVICES ───────────────────────────────── -->
<section class="section section-alt" id="services">
    <div style="text-align:center; margin-bottom: 48px;">
        <div class="section-tag">🔧 Categories</div>
        <h2 class="section-title">Popular Services</h2>
        <p class="section-sub" style="margin: 0 auto;">Browse by category and find exactly what you need.</p>
    </div>

    <div class="categories-grid">
        @php
            $staticCategories = [
                ['icon' => '🔧', 'name' => 'Plumbing',       'desc' => 'Pipes, leaks & more'],
                ['icon' => '💡', 'name' => 'Electrical',     'desc' => 'Wiring & repairs'],
                ['icon' => '🧹', 'name' => 'Cleaning',       'desc' => 'Home & office'],
                ['icon' => '📚', 'name' => 'Tutoring',       'desc' => 'All subjects'],
                ['icon' => '🎨', 'name' => 'Painting',       'desc' => 'Interior & exterior'],
                ['icon' => '🌿', 'name' => 'Landscaping',    'desc' => 'Garden & lawn'],
                ['icon' => '🏗️', 'name' => 'Construction',  'desc' => 'Build & renovate'],
                ['icon' => '🚚', 'name' => 'Moving',         'desc' => 'Relocation help'],
                ['icon' => '❄️', 'name' => 'HVAC',          'desc' => 'AC & heating'],
                ['icon' => '📸', 'name' => 'Photography',    'desc' => 'Events & portraits'],
                ['icon' => '🍳', 'name' => 'Catering',       'desc' => 'Events & meals'],
                ['icon' => '💻', 'name' => 'IT Support',     'desc' => 'Tech & repairs'],
            ];
        @endphp

        @foreach($staticCategories as $cat)
            <a href="{{ route('register') }}" class="category-card">
                <span class="category-icon">{{ $cat['icon'] }}</span>
                <div class="category-name">{{ $cat['name'] }}</div>
                <div class="category-count">{{ $cat['desc'] }}</div>
            </a>
        @endforeach
    </div>
</section>

<!-- ── WHY CHOOSE US ──────────────────────────────────── -->
<section class="section" id="why-us">
    <div style="margin-bottom: 52px;">
        <div class="section-tag">⭐ Our Promise</div>
        <h2 class="section-title">Why Choose ServiLoc?</h2>
        <p class="section-sub">We're built specifically for Ethiopia — with local payment methods, local providers, and local trust.</p>
    </div>

    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon-wrap">✅</div>
            <h3>Verified Providers</h3>
            <p>Every provider goes through a verification process so you know who's coming to your door.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon-wrap">💰</div>
            <h3>Fair & Transparent Pricing</h3>
            <p>Compare multiple offers and choose what fits your budget. No hidden fees, ever.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon-wrap">🔒</div>
            <h3>Secure Escrow Payments</h3>
            <p>Your money is held safely until the job is done. Pay with TeleBirr, CBE Birr, or bank transfer.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon-wrap">💬</div>
            <h3>Direct Communication</h3>
            <p>Chat directly with providers, share details, and stay updated every step of the way.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon-wrap">⭐</div>
            <h3>Real Reviews</h3>
            <p>All reviews come from verified completed jobs. Make informed decisions based on real feedback.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon-wrap">🗂️</div>
            <h3>Provider Portfolios</h3>
            <p>Browse past work and see exactly what a provider can do before you hire them.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon-wrap">🔔</div>
            <h3>Real-time Notifications</h3>
            <p>Get instant updates when you receive offers, messages, or when your payment is released.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon-wrap">🇪🇹</div>
            <h3>Built for Ethiopia</h3>
            <p>Local currency (ETB), local payment methods, and providers you can trust in your community.</p>
        </div>
    </div>
</section>

<!-- ── TESTIMONIALS ───────────────────────────────────── -->
<section class="section section-alt">
    <div style="text-align:center; margin-bottom: 52px;">
        <div class="section-tag">💬 Testimonials</div>
        <h2 class="section-title">What Our Users Say</h2>
        <p class="section-sub" style="margin: 0 auto;">Real stories from real people across Ethiopia.</p>
    </div>

    <div class="testimonials-grid">
        <div class="testimonial-card">
            <div class="stars">★★★★★</div>
            <p class="testimonial-text">"I found a reliable plumber within 2 hours. The whole process was smooth, and I loved that my payment was only released after the job was done. Absolutely amazing platform!"</p>
            <div class="testimonial-author">
                <div class="author-avatar" style="background: linear-gradient(135deg, #0ea5e9, #2563eb);">BT</div>
                <div>
                    <div class="author-name">Bethlehem Tesfaye</div>
                    <div class="author-role">Customer · Addis Ababa</div>
                </div>
            </div>
        </div>
        <div class="testimonial-card">
            <div class="stars">★★★★★</div>
            <p class="testimonial-text">"As an electrician, ServiLoc has completely changed my business. I get consistent work, customers are serious, and the payment system means I always get paid on time."</p>
            <div class="testimonial-author">
                <div class="author-avatar" style="background: linear-gradient(135deg, #059669, #0d9488);">KA</div>
                <div>
                    <div class="author-name">Kaleb Alemu</div>
                    <div class="author-role">Provider · Bole</div>
                </div>
            </div>
        </div>
        <div class="testimonial-card">
            <div class="stars">★★★★☆</div>
            <p class="testimonial-text">"The portfolio feature is brilliant. I can showcase my painting work and customers can see exactly what to expect. Got 3 new clients in my first week!"</p>
            <div class="testimonial-author">
                <div class="author-avatar" style="background: linear-gradient(135deg, #7c3aed, #6366f1);">SM</div>
                <div>
                    <div class="author-name">Sara Mulugeta</div>
                    <div class="author-role">Provider · Piassa</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── CTA BANNER ─────────────────────────────────────── -->
<section class="section">
    <div class="cta-banner">
        <div class="cta-text">
            <h2>Ready to Get Started?</h2>
            <p>Join thousands of satisfied customers and providers across Ethiopia today.</p>
        </div>
        <div class="cta-actions">
            @auth
                <a href="{{ route('customer.requests') }}" class="btn btn-white btn-lg">📝 Post a Request</a>
            @else
                <a href="{{ route('register') }}" class="btn btn-white btn-lg">📝 Post a Request</a>
                <a href="{{ route('register') }}?role=provider" class="btn btn-ghost-white btn-lg">🔧 Become a Provider</a>
            @endauth
        </div>
    </div>
</section>

<!-- ── FOOTER ─────────────────────────────────────────── -->
<footer>
    <div class="footer-top">
        <div class="footer-brand">
            <a href="/" class="logo">
                <div class="icon">🛠️</div>
                <span class="logo-name">Servi<span>Loc</span></span>
            </a>
            <p>Ethiopia's trusted local service marketplace. Connecting customers with verified providers across the country.</p>
        </div>
        <div class="footer-col">
            <h4>Platform</h4>
            <a href="{{ route('register') }}">Post a Request</a>
            <a href="{{ route('register') }}">Find Services</a>
            <a href="{{ route('register') }}">Become a Provider</a>
            <a href="{{ route('login') }}">Sign In</a>
        </div>
        <div class="footer-col">
            <h4>Services</h4>
            <a href="#">Plumbing</a>
            <a href="#">Electrical</a>
            <a href="#">Cleaning</a>
            <a href="#">Tutoring</a>
            <a href="#">View all →</a>
        </div>
        <div class="footer-col">
            <h4>Company</h4>
            <a href="#">About Us</a>
            <a href="#">How it works</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
        </div>
    </div>
    <div class="footer-bottom">
        <span>© {{ date('Y') }} ServiLoc. All rights reserved.</span>
        <span>Made with ❤️ for Ethiopia</span>
    </div>
</footer>

</body>
</html>
