@extends('authy::layouts.main')

@section('content')

<div class="container w-100" style="max-width: 400px">

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Form Title --}}
        <p class="text-center fw-bold fs-5">Access My Account</p>

        {{-- Email --}}
        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="floatingInputEmail" placeholder="name@example.com"
            @error('email') is-invalid @enderror name="email" value="{{ old('email') }}" required autocomplete="email">
            <label for="floatingInputEmail">Email Id</label>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        
        {{-- Password --}}
        <div class="form-floating mb-3">
            <input type="password" class="form-control" id="floatingInputPassword" placeholder="Password"
                name="password" @error('password') is-invalid @enderror required>
            <label for="floatingInputPassword">Password</label>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="btn btn-outline-dark w-100">
            Login Account
        </button>

    </form>

    @if (Route::has('password.request'))
        <a class="btn btn-link text-dark p-0 mt-4" href="{{ route('password.request') }}">
            {{ __('Forgot Your Password?') }}
        </a>
    @endif
    @if (Route::has('register'))
        <div class="d-flex align-items-center mt-3">
            <span>Need an account,</span>
            <a class="btn btn-link text-dark p-0 ms-2" href="{{ route('register') }}">
                Register Here
            </a>
        </div>
    @endif

</div>
@endsection
