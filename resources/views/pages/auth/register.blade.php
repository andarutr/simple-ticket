@extends('layouts.auth')

@section('title','Registrasi')

@section('content')
<div class="page-layout">
    <div class="auth-frame-wrapper">
        <div class="row g-0 h-100">
            <div class="col-lg-12 align-self-center">
                <div class="p-4 p-sm-5 maxw-450px m-auto auth-inner" data-simplebar>
                    <div class="mb-4 text-center">
                        <a href="/register" aria-label="GXON logo">
                            <img class="visible-light" src="/assets/images/logo-full.svg" alt="GXON logo">
                            <img class="visible-dark" src="/assets/images/logo-full-white.svg" alt="GXON logo">
                        </a>
                    </div>
                    <div class="text-center mb-5">
                        <h5 class="mb-1">Selamat Datang di Simple Ticket</h5>
                        <p>Daftarkan Akun Mu Segera!.</p>
                    </div>
                    <form id="registerForm">
                        <div class="mb-4">
                            <label class="form-label" for="name">Nama</label>
                            <input type="text" class="form-control" id="name" placeholder="Masukkan Nama">
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" placeholder="Masukkan Email">
                            <div class="invalid-feedback" id="email-error"></div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="password">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Masukkan Password">
                            <div class="invalid-feedback" id="password-error"></div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary waves-effect waves-light w-100">Daftar</button>
                        </div>
                    </form>
                    <p class="mb-5 text-center">Punya akun? <a href="/login">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#registerForm').on('submit', function(e) {
        e.preventDefault();

        let name = $('#name').val();
        let email = $('#email').val();
        let password = $('#password').val();

        let formData = {
            name: name,
            email: email,
            password: password
        };

        $.ajax({
            type: "POST",
            url: "/register",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                Swal.fire("Berhasil", res.message, "success");
                setTimeout(() => {
                    window.location.href = '/login';
                }, 1500);
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        $('#' + key + '-error').text(errors[key][0]).show();
                        $('#' + key).addClass('is-invalid');
                    });
                } else {
                    Swal.fire('Gagal', xhr.responseText, 'error');
                }
            }
        });
    });

    $('#name, #email, #password').on('input', function() {
        let field = $(this).attr('id');
        $(this).removeClass('is-invalid');
        $('#' + field + '-error').text('').hide();
    });
</script>
@endpush