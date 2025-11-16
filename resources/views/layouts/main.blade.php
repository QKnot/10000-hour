<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | Ten thousand Hour</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon.ico') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --dark-bg: #1a1a2e;
            --card-bg: #16213e;
            --text-light: #e4e4e4;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(138, 75, 175, 0.3) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: none;
            padding: 1rem 0;
            position: relative;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }

        .nav-link {
            color: #333 !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            margin: 0 0.25rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            transform: translateY(-2px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
        }

        .dropdown-menu {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            margin: 0.2rem 0;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateX(5px);
        }

        .dropdown-toggle::after {
            display: none;
        }

        .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dropdown-toggle::before {
            content: '\F4FC';
            font-family: 'bootstrap-icons';
            font-size: 1.2rem;
        }

        .container {
            position: relative;
            z-index: 1;
        }

        .content-wrapper {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            min-height: 400px;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28102, 126, 234, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        @media (max-width: 991px) {
            .navbar-collapse {
                background: rgba(255, 255, 255, 0.98);
                padding: 1rem;
                border-radius: 12px;
                margin-top: 1rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            }
        }

        .user-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        footer {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(102, 126, 234, 0.2);
            padding: 2rem 0;
            margin-top: 3rem;
            position: relative;
            z-index: 1000;
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 0.75rem;
            color: #666;
            font-size: 0.95rem;
            text-align: center;
        }

        .footer-developer {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-content a {
            color: #667eea;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 1.05rem;
        }

        .footer-content a:hover {
            color: #764ba2;
            transform: translateY(-2px);
        }

        .footer-institution {
            font-size: 0.85rem;
            color: #888;
            line-height: 1.6;
            max-width: 500px;
        }

        .footer-heart {
            color: #e74c3c;
            animation: heartbeat 1.5s ease-in-out infinite;
        }

        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            25% { transform: scale(1.1); }
            50% { transform: scale(1); }
        }

        /* Alert System Styles */
        .alert-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 10000;
            max-width: 400px;
            width: auto;
            min-width: 300px;
            pointer-events: none;
        }
        
        .alert-container > * {
            pointer-events: auto;
        }

        .custom-alert {
            background: white;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-left: 4px solid;
            animation: slideInRight 0.3s ease-out;
            position: relative;
            overflow: hidden;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .custom-alert.alert-success {
            border-left-color: #28a745;
            background: linear-gradient(135deg, #d4edda 0%, #ffffff 100%);
        }

        .custom-alert.alert-danger,
        .custom-alert.alert-error {
            border-left-color: #dc3545;
            background: linear-gradient(135deg, #f8d7da 0%, #ffffff 100%);
        }

        .custom-alert.alert-warning {
            border-left-color: #ffc107;
            background: linear-gradient(135deg, #fff3cd 0%, #ffffff 100%);
        }

        .custom-alert.alert-info {
            border-left-color: #17a2b8;
            background: linear-gradient(135deg, #d1ecf1 0%, #ffffff 100%);
        }

        .alert-content {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .alert-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
            margin-top: 0.125rem;
        }

        .alert-success .alert-icon {
            color: #28a745;
        }

        .alert-danger .alert-icon,
        .alert-error .alert-icon {
            color: #dc3545;
        }

        .alert-warning .alert-icon {
            color: #ffc107;
        }

        .alert-info .alert-icon {
            color: #17a2b8;
        }

        .alert-message {
            flex: 1;
            color: #333;
            font-weight: 500;
            line-height: 1.5;
            font-size: 0.95rem;
        }

        .alert-close {
            background: none;
            border: none;
            color: #666;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .alert-close:hover {
            background: rgba(0, 0, 0, 0.1);
            color: #333;
        }

        .custom-alert.slide-out {
            animation: slideOutRight 0.3s ease-out forwards;
        }

        @media (max-width: 768px) {
            .alert-container {
                right: 10px;
                left: 10px;
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ URL('/') }}">
                <i class="bi bi-hourglass-split"></i> 10000 Hour
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('login')) ? 'active' : '' }}" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('register')) ? 'active' : '' }}" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> Register
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('/')) ? 'active' : '' }}" href="{{ route('home') }}">
                                <i class="bi bi-house-door"></i> Home
                            </a>
                        </li>    
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('dashboard')) ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>    
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('badges*')) ? 'active' : '' }}" href="{{ route('badges.index') }}">
                                <i class="bi bi-trophy"></i> Badges
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('leaderboard*')) ? 'active' : '' }}" href="{{ route('leaderboard.index') }}">
                                <i class="bi bi-trophy-fill"></i> Leaderboard
                            </a>
                        </li>
                        @if(Auth::check() && Auth::user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin*')) ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-shield-check"></i> Admin
                            </a>
                        </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->username }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="bi bi-person-circle"></i> My Profile
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav> 

    <!-- Alert Container -->
    <div class="alert-container" id="alert-container"></div>

    <div class="container">
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-developer">
                    <span>Developed by</span>
                    <a href="https://qknot.github.io/Portfolio/" target="_blank">
                        Rahul Ghosh
                        <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                </div>
                <div class="footer-institution">
                    Department of Computer Science<br>
                    Northern University of Business and Technology Khulna
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    
    <!-- Alert System JavaScript -->
    <script>
        // Alert System
        class AlertSystem {
            constructor() {
                this.container = document.getElementById('alert-container');
                if (!this.container) {
                    console.error('Alert container not found!');
                    return;
                }
                this.init();
            }

            init() {
                // Make showAlert available globally first
                const self = this;
                window.showAlert = function(message, type = 'info', duration = 5000) {
                    if (self.container) {
                        self.show(message, type, duration);
                    } else {
                        console.error('Alert system not initialized');
                    }
                };
                
                // Show session flash messages after a small delay to ensure DOM is ready
                setTimeout(() => {
                    this.showSessionAlerts();
                }, 200);
            }

            showSessionAlerts() {
                // Check for Laravel session flash messages
                @if(session('success'))
                    this.show(@json(session('success')), 'success');
                @endif

                @if(session('error'))
                    this.show(@json(session('error')), 'error');
                @endif

                @if(session('warning'))
                    this.show(@json(session('warning')), 'warning');
                @endif

                @if(session('info'))
                    this.show(@json(session('info')), 'info');
                @endif

                // Check for validation errors
                @if($errors->any())
                    @foreach($errors->all() as $error)
                        this.show(@json($error), 'error');
                    @endforeach
                @endif
            }

            show(message, type = 'info', duration = 5000) {
                if (!this.container) {
                    console.error('Alert container not found!');
                    return;
                }
                
                if (!message || message.trim() === '') {
                    return;
                }
                
                const alertId = 'alert-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
                const alert = this.createAlert(message, type, alertId, duration);
                
                this.container.appendChild(alert);
                
                // Auto-close if duration is set
                if (duration > 0) {
                    setTimeout(() => {
                        this.close(alertId);
                    }, duration);
                }
            }

            createAlert(message, type, id, duration) {
                const alert = document.createElement('div');
                alert.className = `custom-alert alert-${type}`;
                alert.id = id;
                alert.setAttribute('data-auto-close', duration > 0 ? 'true' : 'false');
                alert.setAttribute('data-duration', duration);

                const icons = {
                    success: 'bi-check-circle-fill',
                    error: 'bi-x-circle-fill',
                    danger: 'bi-x-circle-fill',
                    warning: 'bi-exclamation-triangle-fill',
                    info: 'bi-info-circle-fill'
                };

                const icon = icons[type] || icons.info;

                alert.innerHTML = `
                    <div class="alert-content">
                        <i class="bi ${icon} alert-icon"></i>
                        <div class="alert-message">${this.escapeHtml(message)}</div>
                        <button type="button" class="alert-close" onclick="window.alertSystem.close('${id}')" aria-label="Close">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                `;

                return alert;
            }

            close(alertId) {
                const alert = document.getElementById(alertId);
                if (alert) {
                    alert.classList.add('slide-out');
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 300);
                }
            }

            escapeHtml(text) {
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, m => map[m]);
            }
        }

        // Initialize alert system when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                window.alertSystem = new AlertSystem();
            });
        } else {
            // DOM already loaded
            window.alertSystem = new AlertSystem();
        }
    </script>
</body>
</html>