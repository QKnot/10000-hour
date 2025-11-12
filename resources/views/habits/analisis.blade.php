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

    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    canvas {
        max-height: 340px !important;
    }

    .heatmap-day {
        width: 11px;
        height: 11px;
        border-radius: 2px;
        margin: 2px;
        display: inline-block;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .heatmap-day:hover {
        border: 1px solid rgba(0,0,0,0.3);
        transform: scale(1.2);
        z-index: 10;
        position: relative;
    }

    .heatmap-container {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        overflow-x: auto;
    }

    .heatmap-grid {
        display: flex;
        gap: 3px;
        align-items: flex-start;
    }

    .heatmap-week {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .heatmap-legend {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 5px;
        margin-top: 10px;
        font-size: 0.75rem;
        color: #666;
    }

    .heatmap-legend-item {
        display: flex;
        align-items: center;
        gap: 3px;
    }

    .heatmap-month-labels {
        display: flex;
        margin-bottom: 5px;
        margin-left: 25px;
        font-size: 0.7rem;
        color: #666;
    }

    .heatmap-day-labels {
        display: flex;
        flex-direction: column;
        gap: 3px;
        margin-right: 5px;
        font-size: 0.7rem;
        color: #666;
        min-width: 20px;
    }

    .heatmap-day-label {
        height: 11px;
        line-height: 11px;
        text-align: right;
        padding-right: 5px;
    }

    .heatmap-tooltip {
        position: absolute;
        background: rgba(0, 0, 0, 0.9);
        color: white;
        padding: 5px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        pointer-events: none;
        z-index: 1000;
        white-space: nowrap;
        display: none;
    }
</style>

<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">
            <i class="bi bi-bar-chart-line me-2"></i>{{ $habit->name }} - Analysis
        </h2>
        <a href="{{ route('habits.index', $habit->id) }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-2"></i>Back to Habit
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4" id="statistics-section">
        <div class="col-md-3">
            <div class="stat-card text-center">
                <div class="stat-value" id="total-hours">-</div>
                <div class="stat-label">Total Hours</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="stat-value" id="avg-hours">-</div>
                <div class="stat-label">Avg Hours/Day</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="stat-value" id="streak">-</div>
                <div class="stat-label">Current Streak</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="stat-value" id="best-day-hours">-</div>
                <div class="stat-label">Best Day</div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card p-4">
                <h6 class="fw-bold text-primary text-center mb-3">📊 Daily Progress (Hours)</h6>
                <div class="chart-container">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-4">
                <h6 class="fw-bold text-success text-center mb-3">🏆 Success vs Failure</h6>
                <div class="chart-container" style="position: relative;">
                    <canvas id="pieChart"></canvas>
                    <div id="centerText"
                        style="position:absolute;top:50%;left:50%;transform:translate(-50%, -50%);
                        font-size:1.4rem;font-weight:700;color:#212529;pointer-events:none;z-index:10;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly and Monthly Charts -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card p-4">
                <h6 class="fw-bold text-info text-center mb-3">📅 Weekly Overview</h6>
                <div class="chart-container">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-4">
                <h6 class="fw-bold text-warning text-center mb-3">📆 Monthly Overview</h6>
                <div class="chart-container">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Habit Info and Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="info-box">
                <h6 class="text-primary fw-bold mb-3">Habit Details</h6>
                <p><span class="metric-label">Name:</span> <span class="text-muted">{{ $habit->name }}</span></p>
                <p><span class="metric-label">Description:</span> <span class="text-muted">{{ $habit->description }}</span></p>
                <p><span class="metric-label">Daily Target:</span> <span class="text-muted">{{ $habit->daily_count }} hours</span></p>
                <p><span class="metric-label">Total Logs:</span> <span class="text-muted">{{ $habit->logs()->count() }} entries</span></p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-box">
                <h6 class="text-primary fw-bold mb-3">Performance Statistics</h6>
                <p><span class="metric-label">Success Days:</span> <span class="text-success fw-bold" id="success-days">-</span></p>
                <p><span class="metric-label">Failure Days:</span> <span class="text-danger fw-bold" id="failure-days">-</span></p>
                <p><span class="metric-label">Success Rate:</span> <span class="text-info fw-bold" id="success-rate">-</span></p>
                <p><span class="metric-label">Goal Progress:</span> <span class="text-primary fw-bold" id="goal-progress">-</span></p>
                <p><span class="metric-label">Goal Status:</span> <span class="text-muted" id="goal-status">In progress</span></p>
                <p><span class="metric-label">Best Day:</span> <span class="text-muted" id="best-day-date">-</span></p>
            </div>
        </div>
    </div>

    <!-- GitHub Style Contribution Calendar -->
    <div class="card p-4 mb-4">
        <h6 class="fw-bold text-primary text-center mb-3">📅 Activity Calendar</h6>
        <div class="heatmap-container">
            <div id="heatmap-calendar" style="min-height: 150px;">
                <div class="text-center text-muted">
                    <i class="bi bi-hourglass-split me-2"></i>Loading calendar...
                </div>
            </div>
            <div class="heatmap-legend" id="heatmap-legend">
                <span>Less</span>
                <div class="heatmap-day" style="background-color: #ebedf0;"></div>
                <div class="heatmap-day" style="background-color: #c6e48b;"></div>
                <div class="heatmap-day" style="background-color: #7bc96f;"></div>
                <div class="heatmap-day" style="background-color: #239a3b;"></div>
                <div class="heatmap-day" style="background-color: #196127;"></div>
                <span>More</span>
            </div>
        </div>
    </div>

    <!-- Time Series Chart -->
    <div class="card p-4 mb-4">
        <h6 class="fw-bold text-primary text-center mb-3">📈 Time Series Analysis</h6>
        <div class="chart-container" style="height: 400px;">
            <canvas id="timeSeriesChart"></canvas>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card mt-4">
        <div class="card-header text-primary fw-bold">
            📅 Recent Activity Logs
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Duration</th>
                            <th>Hours</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $logsByDate = $habit->logs()->orderBy('date', 'desc')->get()->groupBy('date');
                            $targetSeconds = $habit->getDailyTargetSeconds();
                        @endphp
                        @foreach($logsByDate->take(30) as $date => $logs)
                            @php
                                $totalDuration = $logs->sum('duration');
                                $hours = round($totalDuration / 3600, 2);
                                $metTarget = $totalDuration >= $targetSeconds;
                            @endphp
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</td>
                                <td>{{ App\Models\habits::formatDuration($totalDuration) }}</td>
                                <td>{{ $hours }}h</td>
                                <td>
                                    @if($metTarget)
                                        <span class="badge bg-success">Goal Met</span>
                                    @else
                                        <span class="badge bg-warning text-dark">In Progress</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($logsByDate->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center text-muted">No logs found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://unpkg.com/axios@1.6.7/dist/axios.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    // Get data from API
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
    if (!dataset || dataset.code !== 200) {
        document.getElementById('statistics-section').innerHTML = '<div class="col-12"><div class="alert alert-warning">No data available</div></div>';
        return;
    }

    const result = dataset.result;
    const stats = result.statistics || {};
    const logs = result.logs || { index: [], value: [] };
    const info = result.info || { berhasil: 0, gagal: 0 };

    // Update statistics
    const goalHours = stats.goal_hours || {{ $habit->getGoalHours() }};
    const goalProgress = stats.goal_progress || 0;
    const goalReached = !!stats.goal_reached;
    const goalReachedAt = stats.goal_reached_at ? new Date(stats.goal_reached_at) : null;

    const totalHoursValue = stats.total_hours || 0;
    document.getElementById('total-hours').textContent = `${totalHoursValue.toFixed(1)}h / ${goalHours}h`;
    document.getElementById('avg-hours').textContent = `${(stats.average_hours_per_day || 0).toFixed(2)}h`;
    document.getElementById('streak').textContent = (stats.current_streak || 0) + ' days';
    
    if (stats.best_day) {
        document.getElementById('best-day-hours').textContent = stats.best_day.hours.toFixed(1) + 'h';
        document.getElementById('best-day-date').textContent = stats.best_day.formatted_date;
    } else {
        document.getElementById('best-day-hours').textContent = 'N/A';
        document.getElementById('best-day-date').textContent = 'No data yet';
    }

    document.getElementById('success-days').textContent = info.berhasil || 0;
    document.getElementById('failure-days').textContent = info.gagal || 0;
    
    const total = (info.berhasil || 0) + (info.gagal || 0);
    const successRate = total > 0 ? ((info.berhasil / total) * 100).toFixed(1) : 0;
    const successRateText = successRate + '%';
    document.getElementById('success-rate').textContent = successRateText;
    document.getElementById('goal-progress').textContent = goalProgress.toFixed(2) + '%';

    const goalStatusEl = document.getElementById('goal-status');
    if (goalReached) {
        const reachedOn = goalReachedAt
            ? goalReachedAt.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
            : null;
        goalStatusEl.classList.remove('text-muted');
        goalStatusEl.classList.add('text-success', 'fw-bold');
        goalStatusEl.textContent = reachedOn
            ? `Completed on ${reachedOn} 🎉`
            : 'Completed 🎉';
    } else {
        const remaining = Math.max(0, goalHours - totalHoursValue).toFixed(1);
        goalStatusEl.classList.remove('text-success', 'fw-bold');
        goalStatusEl.classList.add('text-muted');
        goalStatusEl.textContent = `${remaining}h remaining`;
    }

    // Daily Chart
    const dailyCtx = document.getElementById('dailyChart');
    if (dailyCtx && logs.index && logs.index.length > 0) {
        // Limit to last 30 days for readability
        const last30Days = Math.min(30, logs.index.length);
        const labels = logs.index.slice(-last30Days).map(date => {
            // Parse date properly (handle YYYY-MM-DD format)
            const dateStr = date.split('T')[0];
            const dateParts = dateStr.split('-');
            const d = new Date(parseInt(dateParts[0]), parseInt(dateParts[1]) - 1, parseInt(dateParts[2]));
            return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });
        const values = logs.value.slice(-last30Days).map(v => parseFloat(v) || 0);

        if (window.dailyChartInstance) {
            window.dailyChartInstance.destroy();
        }

        window.dailyChartInstance = new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Hours',
                    data: values,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { 
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Hours'
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toFixed(2) + ' hours';
                            }
                        }
                    }
                }
            }
        });
    } else if (dailyCtx) {
        // Show message if no data
        dailyCtx.parentElement.innerHTML = '<div class="text-center text-muted p-4">No data available yet. Start logging your activities!</div>';
    }

    // Pie Chart - Fix to ensure it works properly
    const pieCtx = document.getElementById('pieChart');
    const centerText = document.getElementById('centerText');
    const success = parseInt(info.berhasil) || 0;
    const failure = parseInt(info.gagal) || 0;
    const totalPie = success + failure;
    const successRatePie = totalPie > 0 ? ((success / totalPie) * 100).toFixed(1) : 0;

    // Update center text
    if (centerText) {
        centerText.textContent = totalPie > 0 ? successRatePie + "%" : "0%";
        centerText.style.display = totalPie > 0 ? 'block' : 'none';
    }

    // Create pie chart
    if (pieCtx) {
        // Destroy existing chart if it exists
        if (window.pieChartInstance) {
            window.pieChartInstance.destroy();
        }

        // If there's no data, show a message
        if (totalPie === 0) {
            pieCtx.parentElement.innerHTML = '<div class="text-center text-muted p-4">No data available yet. Start logging your activities to see statistics!</div>';
        } else {
            // Ensure we have data for both success and failure
            const pieData = [];
            const pieLabels = [];
            const pieColors = [];
            
            if (success > 0) {
                pieData.push(success);
                pieLabels.push('Success');
                pieColors.push('#28a745');
            }
            
            if (failure > 0) {
                pieData.push(failure);
                pieLabels.push('Failure');
                pieColors.push('#dc3545');
            }
            
            // If only one type exists, add a small slice of the other for better visualization
            if (pieData.length === 1) {
                if (success > 0) {
                    pieData.push(0.1);
                    pieLabels.push('Failure');
                    pieColors.push('#ebedf0');
                } else {
                    pieData.unshift(0.1);
                    pieLabels.unshift('Success');
                    pieColors.unshift('#ebedf0');
                }
            }

            window.pieChartInstance = new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: pieLabels,
                    datasets: [{
                        data: pieData,
                        backgroundColor: pieColors,
                        borderColor: '#fff',
                        borderWidth: 3
                    }]
                },
                options: {
                    cutout: '70%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { 
                                font: { size: 13 }, 
                                color: '#444',
                                padding: 15,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    if (value < 1) return null; // Don't show tooltip for placeholder slices
                                    const percent = totalPie > 0 ? ((value / totalPie) * 100).toFixed(1) : 0;
                                    return `${context.label}: ${value} day${value !== 1 ? 's' : ''} (${percent}%)`;
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
        }
    }

    // Weekly Chart - Fix variable scope issue
    const weeklyCtx = document.getElementById('weeklyChart');
    if (weeklyCtx && stats.weekly_stats && stats.weekly_stats.length > 0) {
        const weeklyData = stats.weekly_stats.slice(0, 12).reverse(); // Last 12 weeks, reversed for chronological order
        
        if (window.weeklyChartInstance) {
            window.weeklyChartInstance.destroy();
        }

        window.weeklyChartInstance = new Chart(weeklyCtx, {
            type: 'line',
            data: {
                labels: weeklyData.map(w => w.label),
                datasets: [{
                    label: 'Hours',
                    data: weeklyData.map(w => parseFloat(w.duration) || 0),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { 
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Hours'
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toFixed(2) + ' hours';
                            }
                        }
                    }
                }
            }
        });
    } else if (weeklyCtx) {
        // Show message if no data
        weeklyCtx.parentElement.innerHTML = '<div class="text-center text-muted p-4">No weekly data available yet.</div>';
    }

    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart');
    if (monthlyCtx && stats.monthly_stats && stats.monthly_stats.length > 0) {
        if (window.monthlyChartInstance) {
            window.monthlyChartInstance.destroy();
        }

        window.monthlyChartInstance = new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: stats.monthly_stats.map(m => m.label),
                datasets: [{
                    label: 'Hours',
                    data: stats.monthly_stats.map(m => parseFloat(m.duration) || 0),
                    backgroundColor: 'rgba(255, 159, 64, 0.7)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { 
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Hours'
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toFixed(2) + ' hours';
                            }
                        }
                    }
                }
            }
        });
    } else if (monthlyCtx) {
        // Show message if no data
        monthlyCtx.parentElement.innerHTML = '<div class="text-center text-muted p-4">No monthly data available yet.</div>';
    }

    // Tooltip functions - Define before calendar generation
    window.showHeatmapTooltip = function(event, date, hours, status) {
        let tooltip = document.getElementById('heatmap-tooltip');
        if (!tooltip) {
            tooltip = document.createElement('div');
            tooltip.className = 'heatmap-tooltip';
            tooltip.id = 'heatmap-tooltip';
            document.body.appendChild(tooltip);
        }
        
        tooltip.textContent = `${date}: ${hours.toFixed(2)}h - ${status}`;
        tooltip.style.display = 'block';
        tooltip.style.left = (event.pageX + 10) + 'px';
        tooltip.style.top = (event.pageY - 30) + 'px';
    };

    window.hideHeatmapTooltip = function() {
        const tooltip = document.getElementById('heatmap-tooltip');
        if (tooltip) {
            tooltip.style.display = 'none';
        }
    };

    // GitHub Style Contribution Calendar
    function generateHeatmapCalendar(logsData, targetHours) {
        const calendarContainer = document.getElementById('heatmap-calendar');
        if (!calendarContainer) return;

        // Create data map from logs
        const dataMap = {};
        if (logsData.index && logsData.value) {
            logsData.index.forEach((date, index) => {
                // Ensure date is in YYYY-MM-DD format
                const dateStr = date.split('T')[0];
                dataMap[dateStr] = parseFloat(logsData.value[index]) || 0;
            });
        }

        // Function to get color intensity (GitHub style)
        function getIntensityColor(hours, target) {
            if (hours === 0) return '#ebedf0'; // No activity - light gray
            if (hours < target * 0.25) return '#c6e48b'; // Light green
            if (hours < target * 0.5) return '#7bc96f'; // Medium green
            if (hours < target * 0.75) return '#239a3b'; // Dark green
            return '#196127'; // Very dark green (goal met or exceeded)
        }

        // Get today (set to midnight)
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        // Find the most recent Sunday (or today if it's Sunday)
        const todayDayOfWeek = today.getDay();
        const lastSunday = new Date(today);
        lastSunday.setDate(today.getDate() - todayDayOfWeek);
        
        // Calculate start date: go back 52 weeks (364 days) from last Sunday
        const startDate = new Date(lastSunday);
        startDate.setDate(startDate.getDate() - 364);
        
        // Ensure start date is a Sunday
        if (startDate.getDay() !== 0) {
            startDate.setDate(startDate.getDate() - startDate.getDay());
        }
        
        // Generate exactly 371 days (53 weeks * 7 days) from start Sunday
        const days = [];
        const currentDate = new Date(startDate);
        const totalDays = 371; // 53 weeks
        
        for (let i = 0; i < totalDays; i++) {
            const dateStr = currentDate.toISOString().split('T')[0];
            const dayCopy = new Date(currentDate);
            days.push({
                date: dateStr,
                hours: dataMap[dateStr] || 0,
                day: currentDate.getDay(), // 0 = Sunday, 6 = Saturday
                dateObj: dayCopy
            });
            currentDate.setDate(currentDate.getDate() + 1);
        }

        // Group days into weeks (each week is a column with 7 days)
        const weeks = [];
        for (let i = 0; i < days.length; i += 7) {
            const week = days.slice(i, i + 7);
            // Ensure week has exactly 7 days (should always be true, but safety check)
            if (week.length === 7) {
                weeks.push(week);
            }
        }

        // Create tooltip element if it doesn't exist
        let tooltip = document.getElementById('heatmap-tooltip');
        if (!tooltip) {
            tooltip = document.createElement('div');
            tooltip.className = 'heatmap-tooltip';
            tooltip.id = 'heatmap-tooltip';
            document.body.appendChild(tooltip);
        }

        // Generate calendar HTML (GitHub style: rows = days of week, columns = weeks)
        let calendarHTML = '<div style="display: flex; align-items: flex-start; gap: 3px;">';
        
        // Day labels column (Sun, Mon, Tue, Wed, Thu, Fri, Sat)
        const dayLabels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        calendarHTML += '<div style="display: flex; flex-direction: column; gap: 3px; min-width: 25px; margin-right: 5px;">';
        dayLabels.forEach((label, index) => {
            // Show only Mon, Wed, Fri to save space (index 1, 3, 5)
            if (index === 1 || index === 3 || index === 5) {
                calendarHTML += `<div style="height: 11px; line-height: 11px; font-size: 0.7rem; color: #666; text-align: right; padding-right: 5px;">${label}</div>`;
            } else {
                calendarHTML += '<div style="height: 11px;"></div>';
            }
        });
        calendarHTML += '</div>';

        // Generate weeks (each week is a column)
        weeks.forEach((week, weekIndex) => {
            calendarHTML += '<div style="display: flex; flex-direction: column; gap: 3px;">';
            // Each day in the week (Sunday = 0, Monday = 1, etc.)
            week.forEach((day, dayIndex) => {
                if (day === null) {
                    // Empty day (shouldn't happen in middle, only at end)
                    calendarHTML += '<div class="heatmap-day" style="background-color: transparent; border: none; cursor: default; width: 11px; height: 11px; visibility: hidden;"></div>';
                } else {
                    const color = getIntensityColor(day.hours, targetHours);
                    // Parse date properly
                    const dateParts = day.date.split('-');
                    const dateObj = new Date(parseInt(dateParts[0]), parseInt(dateParts[1]) - 1, parseInt(dateParts[2]));
                    const dateStr = dateObj.toLocaleDateString('en-US', { 
                        month: 'short', 
                        day: 'numeric', 
                        year: 'numeric' 
                    });
                    const metTarget = day.hours >= targetHours;
                    const statusText = metTarget ? 'Goal Met ✓' : 'In Progress';
                    
                    calendarHTML += `<div 
                        class="heatmap-day" 
                        style="background-color: ${color}; width: 11px; height: 11px; cursor: pointer;"
                        data-date="${day.date}"
                        data-hours="${day.hours.toFixed(2)}"
                        data-status="${statusText}"
                        title="${dateStr}: ${day.hours.toFixed(2)}h - ${statusText}"
                    ></div>`;
                }
            });
            calendarHTML += '</div>';
        });

        calendarHTML += '</div>';

        // Generate month labels
        const monthLabels = [];
        let lastMonth = -1;
        let lastWeekIndex = 0;
        
        weeks.forEach((week, weekIndex) => {
            const firstDay = week.find(d => d !== null);
            if (firstDay) {
                const dateParts = firstDay.date.split('-');
                const dateObj = new Date(parseInt(dateParts[0]), parseInt(dateParts[1]) - 1, parseInt(dateParts[2]));
                const month = dateObj.getMonth();
                
                if (month !== lastMonth || weekIndex === 0) {
                    const monthName = dateObj.toLocaleDateString('en-US', { month: 'short' });
                    monthLabels.push({
                        weekIndex: weekIndex,
                        month: monthName
                    });
                    lastMonth = month;
                    lastWeekIndex = weekIndex;
                }
            }
        });

        // Render month labels above calendar
        let monthLabelsHTML = '<div style="display: flex; margin-bottom: 5px; margin-left: 30px; font-size: 0.7rem; color: #666; align-items: flex-start;">';
        monthLabels.forEach((label, index) => {
            if (index === 0) {
                // First month: account for day labels width
                const width = label.weekIndex * 14;
                monthLabelsHTML += `<span style="min-width: ${width}px; display: inline-block;">${label.month}</span>`;
            } else {
                // Subsequent months: width based on weeks since last month
                const width = (label.weekIndex - monthLabels[index - 1].weekIndex) * 14;
                monthLabelsHTML += `<span style="min-width: ${width}px; display: inline-block;">${label.month}</span>`;
            }
        });
        monthLabelsHTML += '</div>';

        calendarContainer.innerHTML = monthLabelsHTML + calendarHTML;

        // Add event listeners for tooltips
        const heatmapDays = calendarContainer.querySelectorAll('.heatmap-day[data-date]');
        heatmapDays.forEach(day => {
            day.addEventListener('mouseenter', function(e) {
                const date = this.getAttribute('data-date');
                const hours = parseFloat(this.getAttribute('data-hours')) || 0;
                const status = this.getAttribute('data-status');
                
                // Parse date properly
                const dateParts = date.split('-');
                const dateObj = new Date(parseInt(dateParts[0]), parseInt(dateParts[1]) - 1, parseInt(dateParts[2]));
                const dateStr = dateObj.toLocaleDateString('en-US', { 
                    month: 'short', 
                    day: 'numeric', 
                    year: 'numeric' 
                });
                
                if (window.showHeatmapTooltip) {
                    window.showHeatmapTooltip(e, dateStr, hours, status);
                }
            });
            
            day.addEventListener('mouseleave', function() {
                if (window.hideHeatmapTooltip) {
                    window.hideHeatmapTooltip();
                }
            });
        });
    }

    // Generate calendar if we have data
    if (logs.index && logs.index.length > 0) {
        generateHeatmapCalendar(logs, {{ $habit->daily_count }});
    } else {
        document.getElementById('heatmap-calendar').innerHTML = 
            '<div class="text-center text-muted">No activity data available</div>';
    }

    // Time Series Chart
    const timeSeriesCtx = document.getElementById('timeSeriesChart');
    if (timeSeriesCtx && logs.index && logs.index.length > 0) {
        const dates = logs.index.map(date => {
            // Parse date properly (handle YYYY-MM-DD format)
            const dateStr = date.split('T')[0];
            const dateParts = dateStr.split('-');
            const d = new Date(parseInt(dateParts[0]), parseInt(dateParts[1]) - 1, parseInt(dateParts[2]));
            return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        });

        const values = logs.value.map(v => parseFloat(v) || 0);
        const targetValue = parseFloat({{ $habit->daily_count }}) || 0;

        if (window.timeSeriesChartInstance) {
            window.timeSeriesChartInstance.destroy();
        }

        window.timeSeriesChartInstance = new Chart(timeSeriesCtx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Hours Logged',
                    data: values,
                    borderColor: 'rgba(102, 126, 234, 1)',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointHoverRadius: 5
                }, {
                    label: 'Daily Target',
                    data: new Array(values.length).fill(targetValue),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderDash: [5, 5],
                    fill: false,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { 
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Hours'
                        }
                    },
                    x: {
                        ticks: {
                            maxTicksLimit: 15
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + ' hours';
                            }
                        }
                    }
                }
            }
        });
    } else if (timeSeriesCtx) {
        // Show message if no data
        timeSeriesCtx.parentElement.innerHTML = '<div class="text-center text-muted p-4">No time series data available yet. Start logging your activities!</div>';
    }
});
</script>
@endsection
