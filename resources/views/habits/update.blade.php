@extends('layouts.main')
@section('title')
    Update Habits
@endsection
@section('content')
<style>
    .habit-container {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    .habit-card {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: none;
        max-width: 800px;
        width: 100%;
    }

    .habit-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2.5rem 2rem;
        text-align: center;
        border: none;
        position: relative;
    }

    .habit-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.1;
    }

    .habit-header-content {
        position: relative;
        z-index: 1;
    }

    .habit-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        backdrop-filter: blur(10px);
    }

    .habit-icon i {
        font-size: 2.5rem;
    }

    .habit-header h2 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        letter-spacing: -0.5px;
    }

    .habit-name-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 600;
        margin-top: 0.5rem;
        backdrop-filter: blur(10px);
    }

    .habit-body {
        padding: 3rem 2.5rem;
    }

    .form-floating {
        margin-bottom: 1.5rem;
    }

    .form-floating > .form-control,
    .form-floating > .form-control:focus {
        height: auto;
        min-height: 58px;
    }

    .form-floating > textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .form-floating > .form-control {
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

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e0e0e0;
    }

    .btn-update {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 12px;
        flex: 1;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        color: white;
    }

    .btn-update:active {
        transform: translateY(0);
    }

    .btn-cancel {
        background: #f8f9fa;
        border: 2px solid #e0e0e0;
        color: #666;
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 12px;
        flex: 1;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-cancel:hover {
        background: #e9ecef;
        border-color: #667eea;
        color: #667eea;
        transform: translateY(-2px);
    }

    .form-group-wrapper {
        position: relative;
    }

    .char-counter {
        font-size: 0.875rem;
        color: #999;
        text-align: right;
        margin-top: 0.5rem;
    }

    @media (max-width: 768px) {
        .habit-body {
            padding: 2rem 1.5rem;
        }

        .habit-header h2 {
            font-size: 1.5rem;
        }

        .habit-icon {
            width: 60px;
            height: 60px;
        }

        .habit-icon i {
            font-size: 2rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-update,
        .btn-cancel {
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
        }
    }

    .info-tip {
        background: #f8f9fa;
        border-left: 4px solid #667eea;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        display: flex;
        align-items: start;
        gap: 1rem;
    }

    .info-tip i {
        color: #667eea;
        font-size: 1.5rem;
        margin-top: 0.2rem;
    }

    .info-tip-content h6 {
        margin: 0 0 0.5rem 0;
        color: #333;
        font-weight: 600;
    }

    .info-tip-content p {
        margin: 0;
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
    }
</style>

<div class="habit-container">
    <div class="col-12 col-lg-10">
        <div class="habit-card">
            <div class="habit-header">
                <div class="habit-header-content">
                    <div class="habit-icon">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <h2>Update Your Habit</h2>
                    <div class="habit-name-badge">
                        <i class="bi bi-star-fill me-2"></i>{{ $habit->name }}
                    </div>
                </div>
            </div>
            
            <div class="habit-body">
                <div class="info-tip">
                    <i class="bi bi-lightbulb-fill"></i>
                    <div class="info-tip-content">
                        <h6>Pro Tip</h6>
                        <p>Make your habit title clear and specific. A good description helps you stay motivated and track your progress effectively.</p>
                    </div>
                </div>

                <form action="{{ route('habits.update', $habit->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group-wrapper">
                        <div class="form-floating">
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="name" 
                                   value="{{ $habit->name }}"
                                   placeholder="Habit Title"
                                   maxlength="100">
                            <label for="title"><i class="bi bi-bookmark-star me-2"></i>Habit Title</label>
                        </div>
                        @if ($errors->has('title'))
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <span>{{ $errors->first('title') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="form-group-wrapper">
                        <div class="form-floating">
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description"
                                      placeholder="Description (Optional)"
                                      maxlength="500">{{ $habit->description ?? '' }}</textarea>
                            <label for="description"><i class="bi bi-card-text me-2"></i>Description (Optional)</label>
                        </div>
                        @if ($errors->has('description'))
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <span>{{ $errors->first('description') }}</span>
                            </div>
                        @else
                            <div class="char-counter">
                                <i class="bi bi-chat-square-text me-1"></i>
                                Add more details about your habit to stay motivated
                            </div>
                        @endif
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('dashboard') }}" class="btn-cancel">
                            <i class="bi bi-x-circle me-2"></i> Cancel
                        </a>
                        <button type="submit" class="btn-update">
                            <i class="bi bi-check-circle me-2"></i> Update Habit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection