@extends('layouts.main')

@section('title', 'Delete Account')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Delete Account Permanently
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h5 class="alert-heading">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Warning: This Action Cannot Be Undone
                        </h5>
                        <p class="mb-0">
                            Deleting your account will permanently remove all your data including:
                        </p>
                        <ul class="mb-0 mt-2">
                            <li>All your habits ({{ $totalHabits }} habits)</li>
                            <li>All your habit logs and progress ({{ number_format($totalHours, 2) }} hours tracked)</li>
                            <li>All your blog posts ({{ $totalBlogPosts }} posts)</li>
                            <li>All your comments and likes</li>
                            <li>All your earned badges</li>
                            <li>Your profile information</li>
                        </ul>
                    </div>

                    <div class="alert alert-danger">
                        <strong>Important:</strong> Once your account is deleted, there is no way to recover your data. 
                        Please make sure you have backed up any important information before proceeding.
                    </div>

                    @if($totalHours > 0 || $totalHabits > 0 || $totalBlogPosts > 0)
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Your Progress Summary:</h6>
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <div class="stat-box">
                                    <h3 class="text-primary">{{ number_format($totalHours, 2) }}</h3>
                                    <p class="mb-0">Hours Tracked</p>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="stat-box">
                                    <h3 class="text-info">{{ $totalHabits }}</h3>
                                    <p class="mb-0">Habits Created</p>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="stat-box">
                                    <h3 class="text-success">{{ $totalBlogPosts }}</h3>
                                    <p class="mb-0">Blog Posts</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('profile.delete') }}" method="POST" class="mt-4">
                        @csrf
                        @method('DELETE')
                        
                        <div class="mb-3">
                            <label for="confirmation" class="form-label">
                                Type <code>DELETE</code> to confirm:
                            </label>
                            <input type="text" class="form-control" id="confirmation" name="confirmation" 
                                   placeholder="Type DELETE" required>
                            @error('confirmation')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Enter your password to confirm:
                            </label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Enter your password" required>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>
                                Cancel, Keep My Account
                            </a>
                            <button type="submit" class="btn btn-danger" 
                                    onclick="showAlert('⚠️ Deleting account permanently...', 'warning', 2000); return true;">
                                <i class="bi bi-trash me-2"></i>
                                Delete My Account Permanently
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-box {
    padding: 15px;
    border-radius: 8px;
    background: rgba(102, 126, 234, 0.1);
    margin-bottom: 10px;
}

.stat-box h3 {
    margin: 0;
    font-weight: bold;
}

.stat-box p {
    margin: 0;
    font-size: 0.9rem;
    color: #6c757d;
}

.card.border-danger {
    border-width: 2px;
}

.alert {
    border: none;
    border-radius: 10px;
}

.alert-warning {
    background: linear-gradient(135deg, #fff3cd, #ffeeba);
    color: #856404;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da, #f5c6cb);
    color: #721c24;
}

.alert-info {
    background: linear-gradient(135deg, #d1ecf1, #bee5eb);
    color: #0c5460;
}
</style>
@endsection
