@extends('layouts.master-without-nav')

@section('title')
    Login
@endsection

@section('css')

<style>

/* ======================================================
   SG PROFESSIONAL LOGIN PAGE
====================================================== */

.auth-page-wrapper{
    min-height:100vh;

    background:
        linear-gradient(
            135deg,
            #f8fafc 0%,
            #eef2ff 45%,
            #fff1f2 100%
        );

    position:relative;
    overflow:hidden;
}

/* ======================================================
   BACKGROUND EFFECT
====================================================== */

.auth-one-bg{
    position:absolute;
    inset:0;

    background-image:
        radial-gradient(
            rgba(220,38,38,.05) 1px,
            transparent 1px
        );

    background-size:30px 30px;
}

.bg-overlay{
    display:none;
}

.shape{
    display:none;
}

/* ======================================================
   CONTENT CENTER
====================================================== */

.auth-page-content{
    min-height:100vh;

    display:flex;
    align-items:center;
    justify-content:center;

    position:relative;
    z-index:2;
}

/* ======================================================
   LOGO
====================================================== */

.auth-logo img{
    height:120px;
    object-fit:contain;
}

/* ======================================================
   CARD
====================================================== */

.auth-page-content .card{

    border:none;

    border-radius:28px;

    background:rgba(255,255,255,.92);

    backdrop-filter:blur(14px);

    box-shadow:
        0 20px 45px rgba(15,23,42,.08);

    overflow:hidden;

    transition:all .3s ease;
}

.auth-page-content .card:hover{

    transform:translateY(-2px);

    box-shadow:
        0 25px 55px rgba(15,23,42,.12);
}

/* ======================================================
   CARD BODY
====================================================== */

.card-body{
    padding:50px !important;
}

/* ======================================================
   TITLES
====================================================== */

.auth-title{

    font-size:38px;
    font-weight:800;

    color:#000;

    margin-bottom:10px;
}

.auth-subtitle{

    color:#64748b;

    font-size:15px;

    margin-bottom:35px;
}

/* ======================================================
   LABELS
====================================================== */

.form-label{

    font-size:14px;
    font-weight:700;

    color:#334155;

    margin-bottom:10px;
}

/* ======================================================
   INPUTS
====================================================== */

.form-control{

    height:56px;

    border-radius:16px;

    border:1px solid #e2e8f0;

    background:#f8fafc;

    padding-left:18px;

    font-size:14px;

    transition:all .25s ease;
}

.form-control:focus{

    background:#fff;

    border-color:#dc2626;

    box-shadow:
        0 0 0 5px rgba(220,38,38,.10);
}

/* ======================================================
   PASSWORD ICON
====================================================== */

.password-addon{

    height:56px;

    color:#64748b !important;
}

/* ======================================================
   CHECKBOX
====================================================== */

.form-check-input{

    border-radius:6px;
}

.form-check-input:checked{

    background-color:#dc2626;
    border-color:#dc2626;
}

/* ======================================================
   BUTTON
====================================================== */

.btn-primary{

    height:58px;

    border:none;

    border-radius:16px;

  
    font-size:15px;
    font-weight:700;

    letter-spacing:.3px;

    transition:all .3s ease;
}

.btn-primary:hover{

    transform:translateY(-2px);

    box-shadow:
        0 15px 30px rgba(220,38,38,.22);
}

/* ======================================================
   LINKS
====================================================== */

.auth-link{

    color:#dc2626;
    font-weight:700;
}

.auth-link:hover{
    color:#b91c1c;
}

a{
    text-decoration:none !important;
}

/* ======================================================
   FOOTER
====================================================== */

.footer{
    background:transparent;
}

.footer .text-muted{
    color:#64748b !important;
}

/* ======================================================
   MOBILE
====================================================== */

@media(max-width:768px){

    .card-body{
        padding:35px !important;
    }

    .auth-title{
        font-size:30px;
    }

    .auth-logo img{
        height:80px;
    }
}

</style>

@endsection

@section('content')

<div class="auth-page-wrapper">

    <!-- Background -->
    <!-- <div class="auth-one-bg">
        <div class="bg-overlay"></div>
    </div> -->

    <!-- Content -->
    <div class="auth-page-content">

        <div class="container">

            <!-- Logo -->
            <div class="row justify-content-center">

                <div class="col-lg-12">

                    <div class="text-center mb-4">

                        <a href="{{ url('/') }}" class="d-inline-block auth-logo">

                            <img
                                src="{{ URL::asset('build/images/pinghr-removebg-preview.png') }}"
                                alt="logo"
                            >

                        </a>

                    </div>

                </div>

            </div>

            <!-- Card -->
            <div class="row justify-content-center">

                <div class="col-md-8 col-lg-6 col-xl-5">

                    <div class="card">

                        <div class="card-body">

                            <!-- Title -->
                            <div class="text-center">

                                <h2 class="auth-title">
                                    Welcome Back
                                </h2>

                                <p class="auth-subtitle">
                                    Please login to continue your account
                                </p>

                            </div>

                            <!-- Form -->
                            <form method="POST" action="{{ route('login') }}">

                                @csrf

                                <!-- Username -->
                                <div class="mb-3">

                                    <label
                                        for="username"
                                        class="form-label"
                                    >
                                        Username
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control @error('email') is-invalid @enderror"
                                        id="username"
                                        name="email"
                                        value="{{ old('email') }}"
                                        placeholder="Enter username"
                                    >

                                    @error('email')

                                    <span class="invalid-feedback" role="alert">

                                        <strong>{{ $message }}</strong>

                                    </span>

                                    @enderror

                                </div>

                                <!-- Password -->
                                <div class="mb-3">

                                    <div class="d-flex justify-content-between">

                                        <label
                                            class="form-label"
                                            for="password-input"
                                        >
                                            Password
                                        </label>

                                        <a
                                            href="{{ route('password.update') }}"
                                            class="text-muted small"
                                        >
                                            Forgot password?
                                        </a>

                                    </div>

                                    <div class="position-relative auth-pass-inputgroup">

                                        <input
                                            type="password"
                                            class="form-control pe-5 password-input @error('password') is-invalid @enderror"
                                            name="password"
                                            placeholder="Enter password"
                                            id="password-input"
                                        >

                                        <button
                                            class="btn btn-link position-absolute end-0 top-0 text-decoration-none password-addon"
                                            type="button"
                                            id="password-addon"
                                        >

                                            <i class="ri-eye-fill align-middle"></i>

                                        </button>

                                        @error('password')

                                        <span class="invalid-feedback" role="alert">

                                            <strong>{{ $message }}</strong>

                                        </span>

                                        @enderror

                                    </div>

                                </div>

                                <!-- Remember -->
                                <div class="form-check mb-4">

                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="auth-remember-check"
                                    >

                                    <label
                                        class="form-check-label"
                                        for="auth-remember-check"
                                    >
                                        Remember me
                                    </label>

                                </div>

                                <!-- Button -->
                                <div class="mt-4">

                                    <button
                                        class="btn btn-primary w-100"
                                        type="submit"
                                    >
                                        Sign In
                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                    <!-- Signup -->
                    <div class="mt-4 text-center">

                        <p class="mb-0 text-muted">

                            Don't have an account ?

                            <a
                                href="{{ route('register') }}"
                                class="auth-link"
                            >
                                Sign Up
                            </a>

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Footer -->
    <footer class="footer py-4">

        <div class="container">

            <div class="row">

                <div class="col-lg-12">

                    <div class="text-center">

                        <p class="mb-0 text-muted">

                            ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script>

                            SG. All Rights Reserved

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </footer>

</div>

@endsection

@section('script')

<script src="{{ URL::asset('build/js/pages/password-addon.init.js') }}"></script>

@endsection