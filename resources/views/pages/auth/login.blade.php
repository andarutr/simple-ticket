@extends('layouts.auth')

@section('title','Login')

@section('content')
<div class="page-layout">
    <div class="auth-frame-wrapper">
        <div class="row g-0 h-100">
            <div class="col-lg-12 align-self-center">
                <div class="p-4 p-sm-5 maxw-450px m-auto auth-inner" data-simplebar>
                    <div class="mb-4 text-center">
                        <a href="/login" aria-label="GXON logo">
                            <img class="visible-light" src="/assets/images/logo-full.svg" alt="GXON logo">
                            <img class="visible-dark" src="/assets/images/logo-full-white.svg" alt="GXON logo">
                        </a>
                    </div>
                    <div class="text-center mb-5">
                        <h5 class="mb-1">Selamat Datang di Simple Ticket</h5>
                        <p>Silahkan masuk.</p>
                    </div>
                    <form id="loginForm">
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
                            <button type="submit" class="btn btn-primary waves-effect waves-light w-100">Login</button>
                        </div>
                    </form>
                    <p class="mb-5 text-center">Belum punya akun? <a href="/register">Register</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();

        let email = $('#email').val();
        let password = $('#password').val();

        let formData = {
            email: email,
            password: password
        };

        $.ajax({
            type: "POST",
            url: "/login",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                Swal.fire("Berhasil", res.message, "success");
                setTimeout(() => {
                    window.location.href = res.redirect_url;
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
                    Swal.fire('Gagal', xhr.responseText || 'Email atau password salah.', 'error');
                }
            }
        });
    });

    $('#email, #password').on('input', function() {
        let field = $(this).attr('id');
        $(this).removeClass('is-invalid');
        $('#' + field + '-error').text('').hide();
    });
</script>
@endpush