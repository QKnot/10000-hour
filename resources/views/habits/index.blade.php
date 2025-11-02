@extends('layouts.main')

@section('title', 'Habit Analysis')

@section('content')
<style>
    /* ====== Custom Dashboard Styling ====== */
    body {
        background: linear-gradient(145deg, #f0f4f8, #e2ecf5);
        min-height: 100vh;
    }

    .card {
        border: none;
        border-radius: 1.5rem;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-4px);
    }

    .card-body {
        padding: 2rem;
    }

    .text-muted {
        font-size: 0.9rem;
    }

    .progress {
        height: 8px;
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar {
        transition: width 1s ease-in-out;
    }

    #stopwatch-display {
        font-size: 2.5rem;
        font-weight: 600;
        font-family: 'Courier New', monospace;
        letter-spacing: 1px;
    }

    .btn {
        border-radius: 50px;
        padding: 0.6rem 1.4rem;
        transition: all 0.2s ease;
    }

    .btn i {
        margin-right: 6px;
    }

    .btn:hover {
        opacity: 0.9;
        transform: scale(1.02);
    }

    .metric-title {
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .metric-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: #222;
    }
</style>

<div class="container mt-5">
    @if ($message = Session::get('success'))
        <div class="alert alert-success shadow-sm rounded-pill text-center">
            {{ $message }}
        </div>
    @elseif($message = Session::get('warn'))
        <div class="alert alert-warning shadow-sm rounded-pill text-center" id="temp_msg" duration="6000">
            {{ $message }}
        </div>
    @endif

    <div class="row g-4 mb-4">
        <!-- Daily Target -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-4 border-primary">
                <div class="card-body">
                    <div class="metric-title text-primary">Daily Target</div>
                    <div class="metric-value">{{ $habit->daily_count }}h</div>
                    <div class="text-muted">Your goal for today</div>
                </div>
            </div>
        </div>

        <!-- Today Progress -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-4 border-info">
                <div class="card-body">
                    <div class="metric-title text-info">Today’s Progress</div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="metric-value me-3">{{ $habit->getTodayProgress() }}%</div>
                        <div class="flex-grow-1">
                            <div class="progress">
                                <div id="progressBar" class="progress-bar bg-info" 
                                    role="progressbar" 
                                    style="width: 0%" 
                                    aria-valuenow="{{ $habit->getTodayProgress() }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>
                    <small class="text-muted">
                        {{ App\Models\habits::formatDuration(App\Models\habits::getTodayDuration($habit->id)) }} / {{ $habit->daily_count }}h
                    </small>
                </div>
            </div>
        </div>

        <!-- Total Hours -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-4 border-success">
                <div class="card-body">
                    <div class="metric-title text-success">Total Hours</div>
                    <div class="metric-value">{{ App\Models\habits::getTotalHours($habit->id) }} / 10000</div>
                    <small class="text-muted">
                        {{ App\Models\habits::formatDuration(App\Models\habits::getTotalDuration($habit->id)) }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-4 border-secondary">
                <div class="card-body">
                    <div class="metric-title text-secondary">Description</div>
                    <div class="text-dark">{{ $habit->description }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stopwatch Section -->
    <div class="card border-0 shadow-lg p-4">
        <div class="card-body text-center">
            <h5 class="text-primary fw-bold mb-4">⏱ Stopwatch - Track Your Time</h5>
            <div class="d-flex justify-content-center align-items-center mb-3 flex-wrap gap-3">
                <div id="stopwatch-display" class="me-3">00:00:00</div>
                <div>
                    <button id="start-btn" class="btn btn-success"><i class="bi bi-play-fill"></i> Start</button>
                    <button id="pause-btn" class="btn btn-warning" disabled><i class="bi bi-pause-fill"></i> Pause</button>
                    <button id="reset-btn" class="btn btn-danger"><i class="bi bi-arrow-clockwise"></i> Reset</button>
                    <button id="save-btn" class="btn btn-primary" disabled><i class="bi bi-save"></i> Save</button>
                </div>
            </div>
            <p class="text-muted mt-2">
                Total time tracked: <span id="total-hours">0</span> hours <span id="total-minutes">0</span> minutes
            </p>
        </div>
    </div>
</div>

<script>
    // Animate progress bar
    const progressBar = document.getElementById('progressBar');
    const valueNow = parseInt(progressBar.getAttribute('aria-valuenow')) || 0;
    let width = 0;
    const speed = setInterval(() => {
        if (width >= valueNow) clearInterval(speed);
        else progressBar.style.width = (++width) + '%';
    }, 20);

    // Stopwatch functionality
    let stopwatchInterval = null;
    let stopwatchSeconds = 0;
    let isRunning = false;
    const display = document.getElementById('stopwatch-display');
    const habitId = '{{ $habit->id }}';

    const updateDisplay = () => {
        const h = String(Math.floor(stopwatchSeconds / 3600)).padStart(2, '0');
        const m = String(Math.floor((stopwatchSeconds % 3600) / 60)).padStart(2, '0');
        const s = String(stopwatchSeconds % 60).padStart(2, '0');
        display.textContent = `${h}:${m}:${s}`;
        document.getElementById('total-hours').textContent = Math.floor(stopwatchSeconds / 3600);
        document.getElementById('total-minutes').textContent = Math.floor((stopwatchSeconds % 3600) / 60);
    };

    const startBtn = document.getElementById('start-btn');
    const pauseBtn = document.getElementById('pause-btn');
    const resetBtn = document.getElementById('reset-btn');
    const saveBtn = document.getElementById('save-btn');

    startBtn.onclick = () => {
        if (!isRunning) {
            isRunning = true;
            stopwatchInterval = setInterval(() => {
                stopwatchSeconds++;
                updateDisplay();
            }, 1000);
            startBtn.disabled = true;
            pauseBtn.disabled = false;
        }
    };

    pauseBtn.onclick = () => {
        if (isRunning) {
            isRunning = false;
            clearInterval(stopwatchInterval);
            startBtn.disabled = false;
            pauseBtn.disabled = true;
            if (stopwatchSeconds > 0) saveBtn.disabled = false;
        }
    };

    resetBtn.onclick = () => {
        isRunning = false;
        clearInterval(stopwatchInterval);
        stopwatchSeconds = 0;
        updateDisplay();
        startBtn.disabled = false;
        pauseBtn.disabled = true;
        saveBtn.disabled = true;
    };

    saveBtn.onclick = async () => {
        if (stopwatchSeconds === 0) return alert('No time to save!');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';
        try {
            const res = await fetch('{{ route('habits.logtime') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ habit_id: habitId, duration: stopwatchSeconds })
            });
            const data = await res.json();
            if (data.success) {
                alert('✅ Time logged successfully!');
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
                saveBtn.innerHTML = '<i class="bi bi-save"></i> Save';
                saveBtn.disabled = false;
            }
        } catch {
            alert('Failed to save. Try again.');
            saveBtn.innerHTML = '<i class="bi bi-save"></i> Save';
            saveBtn.disabled = false;
        }
    };
</script>
@endsection
