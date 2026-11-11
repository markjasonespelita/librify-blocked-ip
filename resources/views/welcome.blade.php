
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>OLT Network Monitoring System</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #0d6efd;
            --dark: #071426;
            --secondary: #10243e;
            --muted: #9aacc4;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: var(--dark);
            color: #fff;
            overflow-x: hidden;
        }

        .navbar {
            background: rgba(7, 20, 38, .95);
            border-bottom: 1px solid rgba(255,255,255,.08);
            padding: 18px 0;
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: -.5px;
            color: #fff !important;
        }

        .brand-icon {
            color: #38bdf8;
            margin-right: 10px;
        }

        .nav-link {
            color: #b6c4d8 !important;
            font-weight: 500;
            margin: 0 10px;
        }

        .nav-link:hover {
            color: #38bdf8 !important;
        }

        .hero {
            min-height: 85vh;
            display: flex;
            align-items: center;
            position: relative;
            background:
                radial-gradient(circle at 80% 45%, rgba(13,110,253,.19), transparent 35%),
                linear-gradient(135deg, #071426, #10243e);
        }

        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            font-weight: 800;
            line-height: 1.12;
            letter-spacing: -2px;
        }

        .hero h1 span {
            color: #38bdf8;
        }

        .hero p {
            color: var(--muted);
            font-size: 1.08rem;
            line-height: 1.8;
            max-width: 580px;
        }

        .btn-main {
            background: #0d6efd;
            color: #fff;
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            transition: .3s;
        }

        .btn-main:hover {
            background: #0b5ed7;
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-outline {
            border: 1px solid rgba(255,255,255,.25);
            color: #fff;
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-outline:hover {
            background: #fff;
            color: var(--dark);
        }

        .network-visual {
            position: relative;
            text-align: center;
            padding: 40px;
        }

        .network-visual .main-icon {
            font-size: 170px;
            color: #38bdf8;
            filter: drop-shadow(0 0 35px rgba(56,189,248,.35));
        }

        .network-label {
            display: inline-block;
            background: rgba(56,189,248,.1);
            border: 1px solid rgba(56,189,248,.3);
            color: #7dd3fc;
            padding: 10px 18px;
            border-radius: 30px;
            font-size: 13px;
            margin-top: 25px;
        }

        .section {
            padding: 85px 0;
            background: #0a1a30;
        }

        .section-title {
            font-size: 2.2rem;
            font-weight: 800;
        }

        .section-description {
            color: var(--muted);
        }

        .feature-card {
            background: #10243e;
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 14px;
            padding: 30px;
            height: 100%;
            transition: .3s;
        }

        .feature-card:hover {
            transform: translateY(-7px);
            border-color: #38bdf8;
        }

        .feature-icon {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            background: rgba(56,189,248,.12);
            color: #38bdf8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 25px;
        }

        .feature-card h5 {
            font-weight: 700;
            margin-bottom: 12px;
        }

        .feature-card p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.8;
            margin-bottom: 0;
        }

        .cta {
            padding: 80px 0;
            background: linear-gradient(120deg, #0d6efd, #0753a6);
        }

        .cta h2 {
            font-weight: 800;
        }

        footer {
            background: #050e1b;
            padding: 30px 0;
            color: #8193aa;
            font-size: 13px;
        }

        @media(max-width: 991px) {
            .hero {
                padding: 80px 0;
                text-align: center;
            }

            .hero p {
                margin-left: auto;
                margin-right: auto;
            }

            .network-visual .main-icon {
                font-size: 120px;
            }
        }
    </style>
</head>

<body>

<!-- NAVIGATION -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">

        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fas fa-network-wired brand-icon"></i>
            OLT <span class="text-info">NETWORK</span>
        </a>

        <button class="navbar-toggler text-white" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <i class="fas fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="#home">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#features">Features</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#about">About</a>
                </li>

                @if (Route::has('login'))

                    @auth
                        <li class="nav-item">
                            <a href="{{ url('/dashboard') }}" class="btn btn-main ms-lg-3">
                                <i class="fas fa-gauge-high me-2"></i>
                                Dashboard
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-main ms-lg-3">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Login
                            </a>
                        </li>

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a href="{{ route('register') }}" class="nav-link">
                                    Register
                                </a>
                            </li>
                        @endif
                    @endauth

                @endif

            </ul>

        </div>
    </div>
</nav>


<!-- HERO -->
<section class="hero" id="home">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-7">

                <span class="network-label mb-4">
                    <i class="fas fa-circle text-success me-2"></i>
                    NETWORK MANAGEMENT PLATFORM
                </span>

                <h1 class="mt-4">
                    Monitor Your
                    <span>Network.</span>
                    Manage Your
                    <span>Connectivity.</span>
                </h1>

                <p class="mt-4">
                    A centralized network monitoring and management
                    platform designed to help network administrators
                    monitor OLT infrastructure, manage connected sites,
                    and diagnose connectivity issues efficiently.
                </p>

                <div class="d-flex flex-wrap gap-3 mt-4 justify-content-lg-start justify-content-center">

                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-main">
                            <i class="fas fa-gauge-high me-2"></i>
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-main">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Access System
                        </a>
                    @endauth

                    <a href="#features" class="btn btn-outline">
                        Explore Features
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>

                </div>

            </div>

            <div class="col-lg-5">
                <div class="network-visual">

                    <i class="fas fa-server main-icon"></i>

                    <div class="network-label">
                        <i class="fas fa-project-diagram me-2"></i>
                        FIBER OPTIC NETWORK INFRASTRUCTURE
                    </div>

                    <div class="mt-4 text-white-50">
                        <i class="fas fa-circle text-success me-2"></i>
                        Network Monitoring & Management
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>


<!-- FEATURES -->
<section class="section" id="features">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="section-title">Powerful Network Management</h2>
            <p class="section-description mt-3">
                Everything you need to monitor and manage your network infrastructure.
            </p>
        </div>

        <div class="row g-4">

            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-server"></i>
                    </div>
                    <h5>OLT Management</h5>
                    <p>
                        Organize and manage OLT devices, network areas,
                        and their associated infrastructure from one place.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <h5>Site Management</h5>
                    <p>
                        Manage network sites, IP addresses, municipalities,
                        and their corresponding OLT assignments.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h5>Connectivity Monitoring</h5>
                    <p>
                        Check network accessibility and identify
                        potentially unreachable network devices.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h5>Location Management</h5>
                    <p>
                        Organize network infrastructure according
                        to municipalities and designated service areas.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <h5>Network Diagnostics</h5>
                    <p>
                        Simplify network troubleshooting and
                        connectivity investigation for technical personnel.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h5>Centralized Monitoring</h5>
                    <p>
                        Access network information and monitoring
                        resources through a centralized platform.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- ABOUT -->
<section class="section" id="about" style="background:#071426;">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="section-title">
                    One Platform.<br>
                    Better Network Visibility.
                </h2>

                <p class="section-description mt-4">
                    Managing distributed network infrastructure can
                    become challenging as the number of network sites
                    and devices increases.
                </p>

                <p class="section-description">
                    This platform provides a centralized workspace
                    for network administrators and technical personnel
                    to organize infrastructure and monitor connectivity.
                </p>

                <a href="#features" class="btn btn-main mt-3">
                    Discover More
                    <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>

            <div class="col-lg-6">
                <div class="feature-card text-center p-5">
                    <i class="fas fa-network-wired text-info"
                       style="font-size:100px;"></i>

                    <h4 class="mt-4 fw-bold">Network Operations</h4>

                    <p class="mt-3">
                        Centralized infrastructure management
                        for better visibility, organization,
                        and troubleshooting.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- CALL TO ACTION -->
<section class="cta text-center">
    <div class="container">

        <h2>Ready to Manage Your Network?</h2>

        <p class="mt-3 mb-4">
            Access your network management workspace and
            take control of your infrastructure.
        </p>

        @auth
            <a href="{{ url('/dashboard') }}" class="btn btn-light btn-lg fw-bold">
                <i class="fas fa-gauge-high me-2"></i>
                Open Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="btn btn-light btn-lg fw-bold">
                <i class="fas fa-lock me-2"></i>
                Login to Continue
            </a>
        @endauth

    </div>
</section>


<!-- FOOTER -->
<footer>
    <div class="container">
        <div class="row align-items-center">

            <div class="col-md-6 text-center text-md-start">
                <strong class="text-white">
                    <i class="fas fa-network-wired text-info me-2"></i>
                    OLT NETWORK
                </strong>
                <p class="mt-2 mb-0">
                    Network Monitoring and Management System
                </p>
            </div>

            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                &copy; {{ date('Y') }} OLT Network Monitoring System.
                All Rights Reserved.
            </div>

        </div>
    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
