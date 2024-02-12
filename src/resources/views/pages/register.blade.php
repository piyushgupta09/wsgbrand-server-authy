@extends('authy::layouts.main')

@section('content')

<div class="container w-100" style="max-width: 400px">

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Form Title --}}
        <p class="text-center fw-bold fs-5">Create New Account</p>

        {{-- Name --}}
        <div class="form-floating mb-3">
            <input 
                required 
                type="text" 
                name="name" 
                autocomplete="name"
                class="form-control" 
                id="floatingInputName"
                placeholder="John Doe"
                @error('name') is-invalid @enderror 
                value="{{ old('name') }}">
            <label for="floatingInputName">Full name</label>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        
        {{-- Email --}}
        <div class="form-floating mb-3">
            <input 
                required 
                type="email" 
                name="email" 
                autocomplete="email"
                class="form-control" 
                id="floatingInputEmail"
                placeholder="example@gmail.com"
                @error('email') is-invalid @enderror
                value="{{ old('email') }}">
            <label for="floatingInputEmail">Email Id</label>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        
        {{-- Password --}}
        <div class="form-floating mb-3">
            <input 
                required
                type="password" 
                name="password" 
                class="form-control" 
                id="floatingInputPassword"
                placeholder="000000"
                @error('password') is-invalid @enderror>
            <label for="floatingInputPassword">Password</label>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        
        {{-- Confirm Password --}}
        <div class="form-floating mb-3">
            <input 
                required
                type="text" 
                name="password_confirmation" 
                class="form-control" 
                id="floatingInputConfirmPassword"
                placeholder="000000"
                @error('password') is-invalid @enderror>
            <label for="floatingInputConfirmPassword">Confirm Password</label>
        </div>        

        {{-- Submit Button --}}
        <button type="submit" class="btn btn-outline-dark w-100">
            Register Account
        </button>

    </form>

    @if (Route::has('login'))
        <div class="d-flex align-items-center my-4">
            <span>Already have an account,</span>
            <a class="btn btn-link text-dark p-0 ms-2" href="{{ route('login') }}">
                Login Here
            </a>
        </div>
    @endif

</div>
@endsection
