@extends('layouts.main')

@section('title', 'Habit Analysis')

@section('content')
<style>
    /* ===== Modern Dashboard Theme ===== */
    body {
        background: linear-gradient(145deg, #f4f7fc, #e8eef7);
    }

    .card {
        border: none;
        border-radius: 1.2rem;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-4px);
    }

    .chart-container {
        position: relative;
        height: 340px;
        width: 100%;
    }

    .card-header {
        font-weight: 600;
        font-size: 1rem;
        color: #333;
        background: transparent;
        border-bottom: none;
    }

    .info-box {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    }

    .metric-label {
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
    }

    .metric-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: #212529;
    }

    canvas {
        max-height: 340px !important;
    }
</style>

<div class="container py-4">

    <!-- =================== Charts =================== -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card p-4">
                <h6 class="fw-bold text-primary text-center mb-3">📊 Habit Progress (Bar Chart)</h6>
                <div class="chart-container">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-4">
                <h6 class="fw-bold text-success text-center mb-3">🏆 Success vs Failure</h6>
                <div class="chart-container">
                    <canvas id="myPie"></canvas>
                    <div id="centerText"
                        style="position:absolute;top:50%;left:50%;transform:translate(-50%, -50%);
                        font-size:1.4rem;font-weight:700;color:#212529;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- =================== Habit Info =================== -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="info-box">
                <h6 class="text-primary fw-bold mb-3">Habit Details</h6>
                <p><span class="metric-label">ID:</span> <span class="text-muted">{{ $habit->id }}</span></p>
                <p><span class="metric-label">Name:</span> <span class="text-muted">{{ $habit->name }}</span></p>
                <p><span class="metric-label">Description:</span> <span class="text-muted">{{ $habit->description }}</span></p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-box">
                <h6 class="text-primary fw-bold mb-3">Statistics</h6>
                <p><span class="metric-label">Target Hour:</span> <span class="text-muted">{{ $habit->daily_count }}</span></p>
                <p><span class="metric-label">Total Check-ins:</span> <span class="text-muted">{{ $habit->logs()->count() }}</span></p>
            </div>
        </div>
    </div>

    <!-- =================== Logs =================== -->
    <div class="card mt-4">
        <div class="card-header text-primary fw-bold">
            📅 Habit Logs
        </div>
        <div class="card-body">
            <div class="overflow-auto" style="max-height: 400px;">
                @foreach($habit->logs as $log)
                    <div class="row py-2 border-bottom">
                        <div class="col">
                            <strong>Time:</strong> <span class="text-muted">{{ $log->created_at }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- =================== Scripts =================== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://unpkg.com/axios@1.6.7/dist/axios.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const ctxBar = document.getElementById('myChart');
    const ctxPie = document.getElementById('myPie');
    const centerText = document.getElementById('centerText');

    async function getDataset() {
        try {
            const response = await axios.get("{{ route('api.getdata', $habit->id) }}");
            return response.data;
        } catch (error) {
            console.error(error);
            return null;
        }
    }

    const dataset = await getDataset();
    if (!dataset) return;

    const success = dataset.result.info.berhasil || 0;
    const failure = dataset.result.info.gagal || 0;
    const total = success + failure;
    const successRate = total > 0 ? ((success / total) * 100).toFixed(1) : 0;

    // === Center text inside doughnut ===
    centerText.textContent = successRate + "%";

    // ====== Bar Chart ======
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: dataset.result.logs.index,
            datasets: [{
                label: 'Achieved Hours',
                data: dataset.result.logs.value.map(v => Number(v)),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Daily Progress Overview',
                    color: '#333',
                    font: { size: 14, weight: 'bold' }
                }
            }
        }
    });

    // ====== Pie (Doughnut) Chart ======
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ['Success', 'Failure'],
            datasets: [{
                label: 'Performance',
                data: [success, failure],
                backgroundColor: ['#28a745', '#dc3545'],
                borderColor: '#fff',
                borderWidth: 3
            }]
        },
        options: {
            cutout: '70%',
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 13 },
                        color: '#444'
                    }
                },
                title: {
                    display: true,
                    text: 'Success Rate',
                    color: '#222',
                    font: { size: 16, weight: 'bold' }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const percent = ((value / total) * 100).toFixed(1);
                            return `${context.label}: ${value} (${percent}%)`;
                        }
                    }
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });
});
</script>
@endsection
