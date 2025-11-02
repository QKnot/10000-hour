@extends('layouts.main')
@section('title')
    Log in
@endsection
@section('content')
<style>
    .login-page-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 0;
    }

    .login-card-wrapper {
        max-width: 500px;
        width: 100%;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: none;
    }

    .login-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .login-card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.1;
    }

    .login-icon-container {
        position: relative;
        z-index: 1;
    }

    .login-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        backdrop-filter: blur(10px);
        animation: pulse-icon 2s ease-in-out infinite;
    }

    .login-icon i {
        font-size: 2.5rem;
        color: white;
    }

    @keyframes pulse-icon {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 0 0 15px rgba(255, 255, 255, 0);
        }
    }

    .login-card-header h2 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        letter-spacing: -0.5px;
        position: relative;
        z-index: 1;
    }

    .login-card-header p {
        margin: 0;
        opacity: 0.95;
        font-size: 1.05rem;
        position: relative;
        z-index: 1;
    }

    .login-card-body {
        padding: 3rem 2.5rem;
    }

    .form-group-custom {
        margin-bottom: 1.75rem;
    }

    .form-floating {
        position: relative;
    }

    .form-floating > .form-control {
        height: 60px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        transition: all 0.3s ease;
        padding: 1rem 0.75rem;
        font-size: 1rem;
    }

    .form-floating > .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
        outline: none;
    }

    .form-floating > label {
        padding: 1rem 0.75rem;
        color: #666;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        color: #667eea;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
        background-image: none;
    }

    .form-control.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.15);
    }

    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        animation: shake 0.3s ease-in-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    .error-message i {
        font-size: 1rem;
    }

    .btn-login {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 1.1rem 2rem;
        font-size: 1.15rem;
        font-weight: 700;
        border-radius: 12px;
        width: 100%;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }

    .btn-login:active {
        transform: translateY(-1px);
    }

    .divider-section {
        display: flex;
        align-items: center;
        margin: 2rem 0;
        color: #999;
    }

    .divider-section::before,
    .divider-section::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e0e0e0;
    }

    .divider-section span {
        padding: 0 1.5rem;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .register-link-section {
        text-align: center;
        padding-top: 1.5rem;
        border-top: 2px solid #f0f0f0;
    }

    .register-link-section p {
        margin: 0;
        color: #666;
        font-size: 1rem;
    }

    .register-link-section a {
        color: #667eea;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .register-link-section a:hover {
        color: #764ba2;
        gap: 0.75rem;
    }

    .features-mini {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-top: 2.5rem;
        padding-top: 2.5rem;
        border-top: 2px solid #f0f0f0;
    }

    .feature-mini-item {
        text-align: center;
    }

    .feature-mini-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
        transition: all 0.3s ease;
    }

    .feature-mini-item:hover .feature-mini-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transform: translateY(-3px);
    }

    .feature-mini-icon i {
        font-size: 1.5rem;
        color: #667eea;
        transition: all 0.3s ease;
    }

    .feature-mini-item:hover .feature-mini-icon i {
        color: white;
    }

    .feature-mini-label {
        font-size: 0.85rem;
        color: #666;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .login-card-header,
        .login-card-body {
            padding: 2rem 1.5rem;
        }

        .login-icon {
            width: 70px;
            height: 70px;
        }

        .login-icon i {
            font-size: 2rem;
        }

        .login-card-header h2 {
            font-size: 1.75rem;
        }

        .btn-login {
            padding: 1rem 1.5rem;
            font-size: 1rem;
        }

        .features-mini {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }

    .welcome-back-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-top: 1rem;
        backdrop-filter: blur(10px);
    }
</style>

<div class="login-page-container">
    <div class="login-card-wrapper">
        <div class="login-card">
            <!-- Header -->
            <div class="login-card-header">
                <div class="login-icon-container">
                    <div class="login-icon">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </div>
                    <h2>Welcome Back!</h2>
                    <p>Sign in to continue your journey</p>
                    <div class="welcome-back-badge">
                        <i class="bi bi-hourglass-split"></i>
                        10,000 Hour
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="login-card-body">
                <form action="{{ route('authenticate') }}" method="post">
                    @csrf
                    
                    <!-- Email Field -->
                    <div class="form-group-custom">
                        <div class="form-floating">
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   placeholder="Email Address">
                            <label for="email">
                                <i class="bi bi-envelope"></i>
                                Email Address
                            </label>
                        </div>
                        @if ($errors->has('email'))
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <span>{{ $errors->first('email') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Password Field -->
                    <div class="form-group-custom">
                        <div class="form-floating">
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password"
                                   placeholder="Password">
                            <label for="password">
                                <i class="bi bi-lock"></i>
                                Password
                            </label>
                        </div>
                        @if ($errors->has('password'))
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <span>{{ $errors->first('password') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn-login">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Sign In
                    </button>
                </form>

                <!-- Divider -->
                <div class="divider-section">
                    <span>New to 10,000 Hour?</span>
                </div>

                <!-- Register Link -->
                <div class="register-link-section">
                    <p>
                        Don't have an account? 
                        <a href="{{ route('register') }}">
                            Create one now
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </p>
                </div>

                <!-- Mini Features -->
                <div class="features-mini">
                    <div class="feature-mini-item">
                        <div class="feature-mini-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="feature-mini-label">Track Progress</div>
                    </div>
                    <div class="feature-mini-item">
                        <div class="feature-mini-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="feature-mini-label">Build Habits</div>
                    </div>
                    <div class="feature-mini-item">
                        <div class="feature-mini-icon">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <div class="feature-mini-label">Achieve Goals</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection