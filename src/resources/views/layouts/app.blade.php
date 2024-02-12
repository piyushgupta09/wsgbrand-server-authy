@extends('panel::layouts.app')

@section('main')

    <nav class="navbar navbar-expand-lg bg-body-tertiary py-0">
        <div class="container">
            <a class="d-flex text-decoration-none" href="{{ route('panel.welcome') }}">
                <img src="{{ asset(config('brand.logo')) }}" class="of-cover h-80p">
                <div class="d-flex flex-column justify-content-center align-items-start ps-3">
                    <div class="text-dark fw-bold font-title fs-4 p-0">{{ config('brand.name') }}</div>
                    <div class="text-dark font-title lh-1">{{ config('brand.tagline') }}</div>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('panel.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('profiles.show') }}">My Profile</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                @if (session('status'))
                    <div class="alert alert-success mb-3">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
        </div>
        @yield('content')
    </div>

@endsection