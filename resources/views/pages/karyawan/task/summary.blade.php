@extends('layouts.app')

@section('title','Rekap Task per Project')

@push('styles')
<link rel="stylesheet" href="/assets/libs/datatables/datatables.min.css">
@endpush

@section('content')
<div class="col-lg-12">
    <div class="card overflow-hidden">
        <div class="card-header d-flex flex-wrap gap-3 align-items-center justify-content-between">
            <h6 class="card-title mb-0">Rekap Task per Project</h6>
        </div>
        <div class="card-body p-0 pb-3">
            <table class="table display" id="taskSummaryTable">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Project</th>
                        <th>Planning</th>
                        <th>On Progress</th>
                        <th>Done</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($task_summary as $project)
                    <tr>
                        <td class="ps-3">{{ $project['name'] }}</td>
                        <td>{{ $project['planning'] }}</td>
                        <td>{{ $project['on_progress'] }}</td>
                        <td>{{ $project['done'] }}</td>
                        <td><strong>{{ $project['total'] }}</strong></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/assets/libs/datatables/datatables.min.js"></script>

<script>
$(document).ready(function() {
    $('#taskSummaryTable').DataTable({
        dom: 'Bfrtip', 
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Rekap Task per Project',
                className: 'btn btn-success btn-sm',
                text: '<i class="fi fi-rr-download"></i> Excel' 
            }
        ],
        pageLength: 25,
        responsive: true
    });
});
</script>
@endpush