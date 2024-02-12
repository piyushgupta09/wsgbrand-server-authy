@extends('authy::layouts.app')

@php
$user = auth()->user();
@endphp

@section('content')
    <div class="row g-3">
        <div class="col-12">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="position-relative">
                    <img src="{{ $user->getProfileImage() }}" class="card-img-top">
                    @if ($user->userHasMedia())
                    <div class="position-absolute bottom-0 end-0 m-2">
                        <form action="{{ route('profiles.remove-image') }}" method="post">
                            @csrf
                            <input type="hidden" name="uuid" value="{{ $user->uuid }}">
                            <button type="submit" class="btn btn-danger rounded-circle opacity-75">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                <form action="{{ route('profiles.image') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="uuid" value="{{ $user->uuid }}">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="image" class="form-label">Change Profile Image</label>
                            <input class="form-control" type="file" id="image" name="image">
                        </div>
                        <button class="btn btn-dark w-100">Upload Image</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6 col-lg-8">

            <x-authy-auth-toast />

            <div class="card">
                <div class="card-body">
                    <p class="fw-bold fs-5 font-title">My Profile Details:</p>

                    <form action="{{ route('profiles.update') }}" method="POST">
                        @csrf
            
                        <input type="hidden" name="uuid" value="{{ auth()->user()->uuid }}">
            
                        <div class="mb-3">
                            <label for="nameInput" class="form-label">Name</label>
                            <input name="name" type="text" class="form-control" id="nameInput" placeholder="Name"
                                value="{{ auth()->user()->name }}" required>
                        </div>
            
                        <div class="mb-3">
                            <label for="mobile" class="form-label">{{ __('Mobile No.') }}</label>
                            <input id="mobile" type="number" class="form-control" name="mobile"
                                value="{{ auth()->user()->profile?->contacts }}">
                        </div>
            
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('New Password') }}</label>
                            <input id="password" type="password" class="form-control" name="password">
                        </div>
            
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation">
                        </div>
            
                        <div class="d-flex">
                            <button type="reset" class="btn btn-outline-dark px-3 me-2">Reset</button>
                            <button type="submit" class="btn btn-dark px-3">Update</button>
                        </div>
            
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection