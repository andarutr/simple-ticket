@extends('layouts.app')

@section('title','Project')

@push('styles')
<link rel="stylesheet" href="/assets/libs/datatables/datatables.min.css">
@endpush

@section('content')
<div class="col-lg-12">
    <button class="btn btn-primary mb-3" onClick="showModalTambahData()">+ Tambah Data</button>
    <div class="card overflow-hidden">
        <div class="card-header d-flex flex-wrap gap-3 align-items-center justify-content-between">
            <h6 class="card-title mb-0">Project</h6>
            <div id="dt_ScrollVertical_Search"></div>
        </div>
        <div class="card-body p-0 pb-3">
            <table class="table display" id="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
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
            url: "/admin/project/getData"
        },
        columns: [
            { data: "name" },
            { data: "start_date" },
            { data: "end_date" },
            { 
                data: "status",
                render: function(data, type, row){
                    let span = '';

                    if(data == 1){
                        span = `<span class="badge bg-warning">Planning</span>`
                    }else if(data == 2){
                        span = `<span class="badge bg-primary">On Progress</span>`
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
    $("#tambahDataModalLabel").text("Tambah Data Project");

    $("#containertambahDataModal").empty();
    $("#containertambahDataModal").append(`
        <div class="form-group">
            <label>Project</label>
            <input type="text" class="form-control" id="name">    
        </div>
        <div class="form-group mt-4">
            <label>Start Date</label>
            <input type="date" class="form-control" id="start_date">    
        </div>
        <div class="form-group mt-4">
            <label>End Date</label>
            <input type="date" class="form-control" id="end_date">    
        </div>
        <div class="form-group mt-4">
            <label>Status</label>
            <select class="form-control" id="status">  
                <option value="">Pilih</option>
                <option value="1">Planning</option>
                <option value="2">On Progress</option>
                <option value="3">Done</option>
            </select>
        </div>

        <center>
            <button class="btn btn-success mt-4" onClick="submit()">Submit</button>    
        </center>
    `);

    $("#tambahDataModal").modal("show");
}

function submit(){
    let name = $("#name").val();
    let start_date = $("#start_date").val();
    let end_date = $("#end_date").val();
    let status = $("#status").val();

    let formData = {
        name: name,
        start_date: start_date,
        end_date: end_date,
        status: status
    };

    $.ajax({
        type: "POST",
        url: "/admin/project/store",
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
    $.get("/admin/project/edit/" + id, function(res) {
        $("#tambahDataModalLabel").text("Edit Data Project");

        $("#containertambahDataModal").empty();
        $("#containertambahDataModal").append(`
            <input type="hidden" id="id" value="` + res.data.id + `">
            <div class="form-group">
                <label>Project</label>
                <input type="text" class="form-control" id="name" value="` + res.data.name + `">    
            </div>
            <div class="form-group mt-4">
                <label>Start Date</label>
                <input type="date" class="form-control" id="start_date" value="` + res.data.start_date + `">    
            </div>
            <div class="form-group mt-4">
                <label>End Date</label>
                <input type="date" class="form-control" id="end_date" value="` + res.data.end_date + `">    
            </div>
            <div class="form-group mt-4">
                <label>Status</label>
                <select class="form-control" id="status">  
                    <option value="">Pilih</option>
                    <option value="1"` + (res.data.status == 1 ? " selected" : "") + `>Planning</option>
                    <option value="2"` + (res.data.status == 2 ? " selected" : "") + `>On Progress</option>
                    <option value="3"` + (res.data.status == 3 ? " selected" : "") + `>Done</option>
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
    let name = $("#name").val();
    let start_date = $("#start_date").val();
    let end_date = $("#end_date").val();
    let status = $("#status").val();

    let formData = {
        id: id,
        name: name,
        start_date: start_date,
        end_date: end_date,
        status: status
    };

    $.ajax({
        type: "PUT",
        url: "/admin/project/update",
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
                url: "/admin/project/delete",
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
@endpush