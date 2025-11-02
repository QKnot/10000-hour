@extends('layouts.main')
@section('title')
    Welcome to 10,000 Hour
@endsection
@section('content')
<style>
    .welcome-container {
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 0;
    }

    .hero-section {
        text-align: center;
        max-width: 1200px;
        margin: 0 auto;
    }

    .hero-icon-wrapper {
        position: relative;
        display: inline-block;
        margin-bottom: 2rem;
    }

    .hero-icon {
        width: 150px;
        height: 150px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 20px 60px rgba(102, 126, 234, 0.4);
        animation: float 3s ease-in-out infinite;
        position: relative;
        z-index: 2;
    }

    .hero-icon i {
        font-size: 5rem;
        color: white;
    }

    .floating-particles {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 200px;
        height: 200px;
        z-index: 1;
    }

    .particle {
        position: absolute;
        width: 10px;
        height: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        opacity: 0.6;
    }

    .particle:nth-child(1) {
        top: 10%;
        left: 10%;
        animation: particle-float 4s ease-in-out infinite;
    }

    .particle:nth-child(2) {
        top: 20%;
        right: 15%;
        animation: particle-float 3s ease-in-out infinite 0.5s;
    }

    .particle:nth-child(3) {
        bottom: 15%;
        left: 20%;
        animation: particle-float 3.5s ease-in-out infinite 1s;
    }

    .particle:nth-child(4) {
        bottom: 20%;
        right: 10%;
        animation: particle-float 4.5s ease-in-out infinite 1.5s;
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px) rotate(0deg);
        }
        50% {
            transform: translateY(-20px) rotate(5deg);
        }
    }

    @keyframes particle-float {
        0%, 100% {
            transform: translateY(0px);
            opacity: 0.3;
        }
        50% {
            transform: translateY(-30px);
            opacity: 0.8;
        }
    }

    .hero-title {
        font-size: 4rem;
        font-weight: 800;
        margin: 2rem 0 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -2px;
        line-height: 1.2;
    }

    .hero-subtitle {
        font-size: 1.5rem;
        color: #555;
        margin-bottom: 3rem;
        font-weight: 400;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    .cta-buttons {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 4rem;
    }

    .btn-primary-cta {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 1.2rem 3rem;
        font-size: 1.2rem;
        font-weight: 700;
        border-radius: 50px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .btn-primary-cta:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
        color: white;
    }

    .btn-secondary-cta {
        background: white;
        color: #667eea;
        border: 3px solid #667eea;
        padding: 1.2rem 3rem;
        font-size: 1.2rem;
        font-weight: 700;
        border-radius: 50px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
    }

    .btn-secondary-cta:hover {
        background: #667eea;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3);
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin: 5rem 0;
        max-width: 1100px;
        margin-left: auto;
        margin-right: auto;
    }

    .feature-card {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 24px;
        padding: 2.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        text-align: center;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        border-color: #667eea;
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .feature-icon i {
        font-size: 2.5rem;
        color: white;
    }

    .feature-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1rem;
    }

    .feature-description {
        font-size: 1rem;
        color: #666;
        line-height: 1.7;
    }

    .quote-section {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 24px;
        padding: 3rem;
        max-width: 900px;
        margin: 5rem auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .quote-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    }

    .quote-section::after {
        content: '"';
        position: absolute;
        top: 20px;
        left: 30px;
        font-size: 8rem;
        color: #667eea;
        opacity: 0.1;
        font-family: Georgia, serif;
    }

    .quote-text {
        font-size: 1.1rem;
        line-height: 1.9;
        color: #555;
        font-style: italic;
        position: relative;
        z-index: 1;
        margin-bottom: 2rem;
    }

    .quote-author-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 2rem;
        border-top: 2px solid #e0e0e0;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .author-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .author-avatar {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.5rem;
    }

    .author-details h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: #333;
    }

    .author-details p {
        margin: 0.25rem 0 0 0;
        color: #666;
        font-size: 0.95rem;
    }

    .btn-video {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.75rem;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-video:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .stats-showcase {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        max-width: 900px;
        margin: 5rem auto;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 20px;
        padding: 2.5rem 2rem;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 1rem;
        color: #666;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
        }

        .hero-icon {
            width: 100px;
            height: 100px;
        }

        .hero-icon i {
            font-size: 3.5rem;
        }

        .cta-buttons {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-primary-cta,
        .btn-secondary-cta {
            padding: 1rem 2rem;
            font-size: 1rem;
            justify-content: center;
        }

        .features-grid {
            grid-template-columns: 1fr;
        }

        .stats-showcase {
            grid-template-columns: 1fr;
        }

        .quote-section {
            padding: 2rem 1.5rem;
        }

        .quote-author-section {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="welcome-container">
    <div class="col-12">
        <div class="hero-section">
            <!-- Hero -->
            <div class="hero-icon-wrapper">
                <div class="floating-particles">
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                </div>
                <div class="hero-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>

            <h1 class="hero-title">Welcome to 10,000 Hour</h1>
            <p class="hero-subtitle">
                Track your journey to mastery. Build lasting habits. Achieve greatness through deliberate practice.
            </p>

            <div class="cta-buttons">
                <a href="{{ route('register') }}" class="btn-primary-cta">
                    <i class="bi bi-rocket-takeoff"></i>
                    Start Your Journey
                </a>
                <a href="{{ route('login') }}" class="btn-secondary-cta">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Sign In
                </a>
            </div>

            <!-- Stats Showcase -->
            <div class="stats-showcase">
                <div class="stat-card">
                    <div class="stat-number">10,000</div>
                    <div class="stat-label">Hours to Mastery</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">417</div>
                    <div class="stat-label">Days of Practice</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">∞</div>
                    <div class="stat-label">Possibilities</div>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <h3 class="feature-title">Track Progress</h3>
                    <p class="feature-description">
                        Monitor your daily habits and watch your skills grow over time with detailed analytics and insights.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h3 class="feature-title">Build Habits</h3>
                    <p class="feature-description">
                        Create and maintain positive habits that compound into remarkable achievements over time.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <h3 class="feature-title">Achieve Mastery</h3>
                    <p class="feature-description">
                        Reach your 10,000 hours and become an expert in your chosen field through consistent effort.
                    </p>
                </div>
            </div>

            <!-- Quote Section -->
            <div class="quote-section">
                <p class="quote-text">
                    Beginners are often focused on like what to do and I think the focus should be more like how much you do. 
                    So I'm kind of like believer on a high level in this 10,000 hours kind of concept where you just kind of have to 
                    just pick the things where you can spend time and you care about and you're interested in. You literally have to 
                    put in 10,000 hours of work. It doesn't even like matter as much like where you put it and you'll iterate and 
                    you'll improve and you'll waste some time. I don't know if there's a better way you need to put in 10,000 hours 
                    but I think it's actually really nice because I feel like there's some sense of determinism about being an expert 
                    at a thing. If you spend ten thousand hours you can literally pick an arbitrary thing and I think if you spend 
                    ten thousand hours of deliberate effort and work you actually will become an expert at it.
                </p>

                <div class="quote-author-section">
                    <div class="author-info">
                        <div class="author-avatar">AK</div>
                        <div class="author-details">
                            <h4>Andrej Karpathy</h4>
                            <p>AI Research Scientist</p>
                        </div>
                    </div>
                    <a href="https://youtu.be/I2ZK3ngNvvI?si=LOnL06Ghh8OZXE9i" target="_blank" class="btn-video">
                        <i class="bi bi-play-circle-fill"></i>
                        Watch Full Video
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection