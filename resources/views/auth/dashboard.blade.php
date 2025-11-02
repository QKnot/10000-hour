@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0 text-dark">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard
        </h2>
        <button id="toggle-hform" class="btn btn-outline-primary rounded-pill shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Habit
        </button>
    </div>

    <!-- Message Alerts -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success shadow-sm fade show">
            <i class="bi bi-check-circle-fill me-2"></i>{{ $message }}
        </div>
    @elseif($message = Session::get('warn'))
        <div class="alert alert-warning shadow-sm fade show" id="temp_msg" duration="6000">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $message }}
        </div>
    @else
        <div class="alert alert-info shadow-sm fade show" id="temp_msg" duration="3000">
            <i class="bi bi-person-check-fill me-2"></i>You are logged in!
        </div>
    @endif

    @error('name')
        <div class="alert alert-danger shadow-sm">
            <i class="bi bi-x-circle-fill me-2"></i>{{ $message }}
        </div>
    @enderror

    <!-- Hidden Form -->
    <div id="hiddenform" class="card card-body border-0 shadow-sm p-4 mb-4" style="display: none;">
        <form class="row g-3 align-items-center" action="{{ route('habits.store') }}" method="POST">
            @csrf
            <div class="col-md-6">
                <input name="name" class="form-control form-control-lg" type="text" placeholder="Enter Habit Name" required>
            </div>
            <div class="col-md-4">
                <input name="daily_count" class="form-control form-control-lg" type="number" placeholder="Target Hours" min="1" max="10000" required>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                    <i class="bi bi-check2-circle"></i> Save
                </button>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Habit</th>
                            <th>Progress</th>
                            <th>Target</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($habits as $index => $habit)
                        <tr class="fade-in">
                            <td class="fw-semibold">{{ $index + 1 }}</td>
                            <td class="fw-bold text-dark">{{ $habit->name }}</td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {!! App\Models\habits::formatDuration(App\Models\habits::getTodayDuration($habit->id)) !!}
                                </span>
                                <small class="text-muted d-block">
                                    Total: {{ App\Models\habits::getTotalHours($habit->id) }}h
                                </small>
                            </td>
                            <td><span class="fw-semibold">{{ $habit->daily_count }}h</span></td>
                            <td>
                                @if ($habit->getTodayProgress() >= 100)
                                    <span class="badge bg-success rounded-pill px-3">Done</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3">Unfinished</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a class="btn btn-outline-info btn-sm" href="{{ route('habits.analisis', $habit->id) }}" title="Analytics">
                                        <i class="bi bi-bar-chart-line"></i>
                                    </a>
                                    <a class="btn btn-outline-dark btn-sm" href="{{ route('habits.index', $habit->id) }}" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a class="btn btn-outline-warning btn-sm" href="{{ route('habits.updatepage', $habit->id) }}" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('habits.destroy', $habit->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" type="submit" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-emoji-frown fs-4 d-block"></i>
                                No habits found. Add one to get started!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Style -->
<style>
    body {
        background-color: #f8f9fa;
    }
    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(10px);}
        to {opacity: 1; transform: translateY(0);}
    }
</style>

<!-- Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const divHidden = document.getElementById('hiddenform');
    const btnToggle = document.getElementById('toggle-hform');
    const divMsg = document.getElementById('temp_msg');

    btnToggle.addEventListener('click', function() {
        if (divHidden.style.display === "none") {
            divHidden.style.display = "block";
            divHidden.classList.add("fade-in");
        } else {
            divHidden.style.display = "none";
        }
    });

    if (divMsg) {
        const duration = divMsg.getAttribute('duration');
        setTimeout(() => {
            divMsg.classList.add('fade');
            setTimeout(() => divMsg.remove(), 500);
        }, duration);
    }
});
</script>
@endsection
