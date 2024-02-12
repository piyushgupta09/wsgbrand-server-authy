@extends('panel::layouts.app')

@section('main')
<div class="d-flex flex-column justify-content-evenly align-items-center" style="height: calc(100vh - 50px)">
    <div class="flex-fill d-flex flex-column justify-content-center">
        <div class="d-flex flex-column justify-content-center align-items-center">
            <img src="{{ asset(config('brand.logo')) }}" class="d-none d-md-block" style="width: 300px;">
            <img src="{{ asset(config('brand.logo')) }}" class="d-md-none d-block" style="width: 200px;">
            <div class="py-2 px-4 text-bg-light rounded shadow border">
                <p class="d-none d-md-block fs-5 fw-bold ls-1 mb-0 font-subtitle">{{ config('brand.tagline') }}</p>
                <p class="d-md-none d-block fs-6 fw-bold ls-1 mb-0 font-subtitle">{{ config('brand.tagline') }}</p>
            </div>
        </div>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @yield('content')
    <div class="mb-5"></div>
</div>
@endsection
