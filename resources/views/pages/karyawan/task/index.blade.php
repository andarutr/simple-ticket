@extends('layouts.app')

@section('title','Task')

@push('styles')
<link rel="stylesheet" href="/assets/libs/datatables/datatables.min.css">
@endpush

@section('content')
<div class="col-lg-12">
    <button class="btn btn-primary mb-3" onClick="showModalTambahData()">+ Tambah Data</button>
    <div class="card overflow-hidden">
        <div class="card-header d-flex flex-wrap gap-3 align-items-center justify-content-between">
            <h6 class="card-title mb-0">Task</h6>
            <div id="dt_ScrollVertical_Search"></div>
        </div>
        <div class="card-body p-0 pb-3">
            <table class="table display" id="table">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Priority</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tambahDataModalLabel"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="containertambahDataModal"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/assets/libs/datatables/datatables.min.js"></script>
<script src="/assets/js/datatable.js"></script>
<script>
function getData(){
    $("#table").DataTable({
        serverSide: true,
        ajax: {
            type: "GET",
            url: "/karyawan/task/getData"
        },
        columns: [
            { 
                data: "project.name",
                defaultContent: '-'
            },
            { data: "title" },
            { 
                data: "description",
                render: function(data, type, row){
                    return `<div style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">` + data + `</div>`;
                }
            },
            { 
                data: "priority",
                render: function(data, type, row){
                    let span = '';

                    if(data == 1){
                        span = `<span class="badge bg-secondary">Low</span>`
                    }else if(data == 2){
                        span = `<span class="badge bg-warning">Medium</span>`
                    }else{
                        span = `<span class="badge bg-danger">High</span>`
                    }

                    return span;
                }
            },
            { data: "deadline" },
            { 
                data: "status",
                render: function(data, type, row){
                    let span = '';

                    if(data == 1){
                        span = `<span class="badge bg-secondary">Todo</span>`
                    }else if(data == 2){
                        span = `<span class="badge bg-primary">Doing</span>`
                    }else if(data == 3){
                        span = `<span class="badge bg-info">Review</span>`
                    }else{
                        span = `<span class="badge bg-success">Done</span>`
                    }

                    return span;
                }
            },
            {
                data: null,
                render: function(data, type, row){
                    return `
                        <button class="btn btn-success" onClick="editData(` + row.id + `)"><i class="fi fi-rr-pencil"></i></button>
                        <button class="btn btn-danger" onClick="deleteData(` + row.id + `)"><i class="fi fi-rr-trash"></i></button>
                    `;
                }
            }
        ]
    })
} getData();

function showModalTambahData(){
    $.get("/karyawan/task/create", function(res) {
        $("#tambahDataModalLabel").text("Tambah Data Task");

        $("#containertambahDataModal").empty();
        $("#containertambahDataModal").append(`
            <div class="form-group">
                <label>Project</label>
                <select class="form-control" id="project_id">
                    <option value="">Pilih</option>
                    ` + res.projects.map(p => `<option value="` + p.id + `">` + p.name + `</option>`).join('') + `
                </select>
            </div>
            <div class="form-group mt-4">
                <label>Title</label>
                <input type="text" class="form-control" id="title">    
            </div>
            <div class="form-group mt-4">
                <label>Description</label>
                <textarea class="form-control" id="description" rows="3"></textarea>
            </div>
            <div class="form-group mt-4">
                <label>Priority</label>
                <select class="form-control" id="priority">  
                    <option value="">Pilih</option>
                    <option value="1">Low</option>
                    <option value="2">Medium</option>
                    <option value="3">High</option>
                </select>
            </div>
            <div class="form-group mt-4">
                <label>Deadline</label>
                <input type="date" class="form-control" id="deadline">    
            </div>
            <div class="form-group mt-4">
                <label>Status</label>
                <select class="form-control" id="status">  
                    <option value="">Pilih</option>
                    <option value="1">Todo</option>
                    <option value="2">Doing</option>
                    <option value="3">Review</option>
                    <option value="4">Done</option>
                </select>
            </div>

            <center>
                <button class="btn btn-success mt-4" onClick="submit()">Submit</button>    
            </center>
        `);

        $("#tambahDataModal").modal("show");
    });
}

function submit(){
    let project_id = $("#project_id").val();
    let title = $("#title").val();
    let description = $("#description").val();
    let priority = $("#priority").val();
    let deadline = $("#deadline").val();
    let status = $("#status").val();

    let formData = {
        project_id: project_id,
        title: title,
        description: description,
        priority: priority,
        deadline: deadline,
        status: status
    };

    $.ajax({
        type: "POST",
        url: "/karyawan/task/store",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res){
            Swal.fire("Berhasil", res.message, "success");
            $("#tambahDataModal").modal("hide");
            $("#table").DataTable().ajax.reload();
        },
        error: function(xhr){
            if (xhr.status === 422) {
                let errors = Object.values(xhr.responseJSON.errors).flat();
                Swal.fire('Gagal', errors.join('<br>'), 'error');
            } else {
                Swal.fire('Gagal', xhr.responseText, 'error');
            }
        }
    })
}

function editData(id) {
    $.get("/karyawan/task/edit/" + id, function(res) {
        $("#tambahDataModalLabel").text("Edit Data Task");

        $("#containertambahDataModal").empty();
        $("#containertambahDataModal").append(`
            <input type="hidden" id="id" value="` + res.data.id + `">
            <div class="form-group">
                <label>Project</label>
                <select class="form-control" id="project_id">
                    <option value="">Pilih</option>
                    ` + res.projects.map(p => `<option value="` + p.id + `"` + (p.id == res.data.project_id ? " selected" : "") + `>` + p.name + `</option>`).join('') + `
                </select>
            </div>
            <div class="form-group mt-4">
                <label>Title</label>
                <input type="text" class="form-control" id="title" value="` + res.data.title + `">    
            </div>
            <div class="form-group mt-4">
                <label>Description</label>
                <textarea class="form-control" id="description" rows="3">` + res.data.description + `</textarea>
            </div>
            <div class="form-group mt-4">
                <label>Priority</label>
                <select class="form-control" id="priority">  
                    <option value="">Pilih</option>
                    <option value="1"` + (res.data.priority == 1 ? " selected" : "") + `>Low</option>
                    <option value="2"` + (res.data.priority == 2 ? " selected" : "") + `>Medium</option>
                    <option value="3"` + (res.data.priority == 3 ? " selected" : "") + `>High</option>
                </select>
            </div>
            <div class="form-group mt-4">
                <label>Deadline</label>
                <input type="date" class="form-control" id="deadline" value="` + res.data.deadline + `">    
            </div>
            <div class="form-group mt-4">
                <label>Status</label>
                <select class="form-control" id="status">  
                    <option value="">Pilih</option>
                    <option value="1"` + (res.data.status == 1 ? " selected" : "") + `>Todo</option>
                    <option value="2"` + (res.data.status == 2 ? " selected" : "") + `>Doing</option>
                    <option value="3"` + (res.data.status == 3 ? " selected" : "") + `>Review</option>
                    <option value="4"` + (res.data.status == 4 ? " selected" : "") + `>Done</option>
                </select>
            </div>

            <center>
                <button class="btn btn-success mt-4" onClick="update()">Update</button>    
            </center>
        `);

        $("#tambahDataModal").modal("show");
    });
}

function update() {
    let id = $("#id").val();
    let project_id = $("#project_id").val();
    let title = $("#title").val();
    let description = $("#description").val();
    let priority = $("#priority").val();
    let deadline = $("#deadline").val();
    let status = $("#status").val();

    let formData = {
        id: id,
        project_id: project_id,
        title: title,
        description: description,
        priority: priority,
        deadline: deadline,
        status: status
    };

    $.ajax({
        type: "PUT",
        url: "/karyawan/task/update",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res){
            Swal.fire("Berhasil", res.message, "success");
            $("#tambahDataModal").modal("hide");
            $("#table").DataTable().ajax.reload();
        },
        error: function(xhr){
            if (xhr.status === 422) {
                let errors = Object.values(xhr.responseJSON.errors).flat();
                Swal.fire('Gagal', errors.join('<br>'), 'error');
            } else {
                Swal.fire('Gagal', xhr.responseText, 'error');
            }
        }
    })
}

function deleteData(id) {
    Swal.fire({
        title: "Apakah kamu yakin?",
        text: "Data ini akan dihapus secara permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "DELETE",
                url: "/karyawan/task/delete",
                data: { id },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res){
                    Swal.fire("Berhasil", res.message, "success");
                    $("#table").DataTable().ajax.reload();
                },
                error: function(xhr){
                    Swal.fire('Gagal', xhr.responseText, 'error');
                }
            });
        }
    });
}
</script>
@endpush>