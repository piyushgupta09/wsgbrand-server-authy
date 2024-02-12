@extends('authy::layouts.main')

@section('content')
<div class="container w-100" style="max-width: 400px">

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        {{-- Form Title --}}
        <p class="text-center lead">Forgot Password</p>
        
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
        

        {{-- Submit Button --}}
        <button type="submit" class="btn btn-outline-dark w-100">
            Send Password Reset Link
        </button>

    </form>

    @if (Route::has('login'))
        <div class="d-flex align-items-center my-4">
            <span>Got your password, then</span>
            <a class="btn btn-link text-dark p-0 ms-1" href="{{ route('login') }}">
                Login Here
            </a>
        </div>
    @endif

</div>
@endsection
