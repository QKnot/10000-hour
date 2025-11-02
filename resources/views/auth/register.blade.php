@extends('layouts.main')
@section('title')
    Register
@endsection
@section('content')
<style>
    .register-container {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    .register-card {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: none;
        max-width: 900px;
        width: 100%;
    }

    .register-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2.5rem 2rem;
        text-align: center;
        border: none;
    }

    .register-header h2 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.5px;
    }

    .register-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.95;
        font-size: 1rem;
    }

    .register-body {
        padding: 3rem 2.5rem;
    }

    .form-floating {
        margin-bottom: 1.5rem;
    }

    .form-floating > .form-control {
        height: 58px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        transition: all 0.3s ease;
        padding: 1rem 0.75rem;
    }

    .form-floating > .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    }

    .form-floating > label {
        padding: 1rem 0.75rem;
        color: #666;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
        background-image: none;
    }

    .form-control.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
    }

    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .error-message i {
        font-size: 1rem;
    }

    .btn-register {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 12px;
        width: 100%;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 1rem;
    }

    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }

    .btn-register:active {
        transform: translateY(0);
    }

    .login-link {
        text-align: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e0e0e0;
        color: #666;
    }

    .login-link a {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .login-link a:hover {
        color: #764ba2;
        text-decoration: underline;
    }

    .input-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 1.2rem;
        pointer-events: none;
        z-index: 5;
    }

    .form-group-wrapper {
        position: relative;
    }

    @media (max-width: 768px) {
        .register-body {
            padding: 2rem 1.5rem;
        }

        .register-header h2 {
            font-size: 1.5rem;
        }

        .btn-register {
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
        }
    }

    .password-strength {
        margin-top: 0.5rem;
        font-size: 0.875rem;
    }

    .strength-bar {
        height: 4px;
        background: #e0e0e0;
        border-radius: 2px;
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .strength-bar-fill {
        height: 100%;
        transition: all 0.3s ease;
        background: linear-gradient(90deg, #dc3545 0%, #ffc107 50%, #28a745 100%);
    }
</style>

<div class="register-container">
    <div class="col-12 col-lg-10">
        <div class="register-card">
            <div class="register-header">
                <i class="bi bi-person-plus-fill" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <h2>Create Account</h2>
                <p>Join 10000 Hour and start tracking your journey to mastery</p>
            </div>
            <div class="register-body">
                <form action="{{ route('store') }}" method="post">
                    @csrf
                    
                    <div class="form-group-wrapper">
                        <div class="form-floating">
                            <input type="text" 
                                   class="form-control @error('username') is-invalid @enderror" 
                                   id="username" 
                                   name="username" 
                                   value="{{ old('username') }}"
                                   placeholder="Username">
                            <label for="username"><i class="bi bi-person me-2"></i>Username</label>
                        </div>
                        @if ($errors->has('username'))
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <span>{{ $errors->first('username') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="form-group-wrapper">
                        <div class="form-floating">
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   placeholder="Email Address">
                            <label for="email"><i class="bi bi-envelope me-2"></i>Email Address</label>
                        </div>
                        @if ($errors->has('email'))
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <span>{{ $errors->first('email') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="form-group-wrapper">
                        <div class="form-floating">
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password"
                                   placeholder="Password">
                            <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
                        </div>
                        @if ($errors->has('password'))
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <span>{{ $errors->first('password') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="form-group-wrapper">
                        <div class="form-floating">
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation"
                                   placeholder="Confirm Password">
                            <label for="password_confirmation"><i class="bi bi-shield-check me-2"></i>Confirm Password</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-register">
                        <i class="bi bi-rocket-takeoff me-2"></i> Create Account
                    </button>

                    <div class="login-link">
                        Already have an account? <a href="{{ route('login') }}">Login here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection