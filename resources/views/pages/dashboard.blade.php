@extends('layouts.app')

@section('title','Dashboard')

@push('styles')
<link rel="stylesheet" href="/assets/libs/datatables/datatables.min.css">
@endpush

@section('content')
<div class="row">
    <div class="col-xxl-9">
        <div class="row">
            <!-- Total Project -->
            <div class="col-6 col-md-4 col-lg">
                <div class="card bg-secondary bg-opacity-05 shadow-none border-0">
                    <div class="card-body">
                        <div class="avatar bg-secondary shadow-secondary rounded-circle text-white mb-3">
                            <i class="fi fi-rr-layers"></i> <!-- Ikon untuk total project -->
                        </div>
                        <h3>{{ $total_project }}</h3>
                        <h6 class="mb-0">Total Project</h6>
                    </div>
                </div>
            </div>

            <!-- Planning -->
            <div class="col-6 col-md-4 col-lg">
                <div class="card bg-info bg-opacity-05 shadow-none border-0">
                    <div class="card-body">
                        <div class="avatar bg-info shadow-info rounded-circle text-white mb-3">
                            <i class="fi fi-rr-calendar-clock"></i> <!-- Ikon untuk planning -->
                        </div>
                        <h3>{{ $planning }}</h3>
                        <h6 class="mb-0">Planning</h6>
                    </div>
                </div>
            </div>

            <!-- On Progress -->
            <div class="col-6 col-md-4 col-lg">
                <div class="card bg-warning bg-opacity-05 shadow-none border-0">
                    <div class="card-body">
                        <div class="avatar bg-warning shadow-warning rounded-circle text-white mb-3">
                            <i class="fi fi-rr-sync"></i> <!-- Ikon untuk on progress -->
                        </div>
                        <h3>{{ $on_progress }}</h3>
                        <h6 class="mb-0">On Progress</h6>
                    </div>
                </div>
            </div>

            <!-- Done -->
            <div class="col-6 col-md-6 col-lg">
                <div class="card bg-success bg-opacity-05 shadow-none border-0">
                    <div class="card-body">
                        <div class="avatar bg-success shadow-success rounded-circle text-white mb-3">
                            <i class="fi fi-rr-check-circle"></i> <!-- Ikon untuk done -->
                        </div>
                        <h3>{{ $done }}</h3>
                        <h6 class="mb-0">Done</h6>
                    </div>
                </div>
            </div>

            <!-- Total Task -->
            <div class="col-6 col-md-6 col-lg">
                <div class="card bg-danger bg-opacity-05 shadow-none border-0">
                    <div class="card-body">
                        <div class="avatar bg-danger shadow-danger rounded-circle text-white mb-3">
                            <i class="fi fi-rr-list"></i> <!-- Ikon untuk task -->
                        </div>
                        <h3>{{ $task }}</h3>
                        <h6 class="mb-0">Task</h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts & Latest Tasks -->
        <div class="row mt-4">
            <!-- Donut Chart: Progress Project -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Progress Task per Status</h5>
                    </div>
                    <div class="card-body text-center">
                        <canvas id="donutChart" width="400" height="400"></canvas>
                    </div>
                </div>
            </div>

            <!-- Bar Chart: Task Done per Bulan -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Jumlah Task Selesai per Bulan</h5>
                    </div>
                    <div class="card-body text-center">
                        <canvas id="barChart" width="400" height="400"></canvas>
                    </div>
                </div>
            </div>

            <!-- Table: Latest Tasks -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">5 Task Terbaru</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Title</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($latest_tasks as $task)
                                    <tr>
                                        <td class="ps-3">{{ Str::limit($task->title, 20) }}</td>
                                        <td>
                                            @if($task->status == 1)
                                                <span class="badge bg-secondary">Todo</span>
                                            @elseif($task->status == 2)
                                                <span class="badge bg-primary">Doing</span>
                                            @elseif($task->status == 3)
                                                <span class="badge bg-info">Review</span>
                                            @else
                                                <span class="badge bg-success">Done</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/assets/libs/datatables/datatables.min.js"></script>
<script src="/assets/libs/chartjs/chart.js"></script>
<script>
    const taskStatusData = {
        done: {{ $task_done }},
        onProgress: {{ $task_on_progress }},
        planning: {{ $task_planning }}
    };

    const taskMonthlyData = @json($task_monthly);

    // Donut Chart
    const donutCtx = document.getElementById('donutChart').getContext('2d');
    new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            labels: ['Done', 'On Progress', 'Planning'],
            datasets: [{
                data: [taskStatusData.done, taskStatusData.onProgress, taskStatusData.planning],
                backgroundColor: [
                    '#28a745', // Done
                    '#ffc107', // On Progress
                    '#17a2b8'  // Planning
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Bar Chart
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: taskMonthlyData.labels,
            datasets: [{
                label: 'Task Selesai',
                data: taskMonthlyData.data,
                backgroundColor: '#28a745',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush