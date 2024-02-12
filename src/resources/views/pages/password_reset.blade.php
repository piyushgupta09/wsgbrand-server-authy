@extends('authy::layouts.main')

@section('content')
<div class="container w-100" style="max-width: 400px">

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        {{-- Form Title --}}
        <p class="text-center lead">Reset Account Password</p>

        {{-- Password --}}
        <div class="form-floating mb-3">
            <input type="password" class="form-control" id="floatingInputPassword" placeholder="Password"
                name="password" @error('password') is-invalid @enderror required autofocus>
            <label for="floatingInputPassword">Password</label>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingInputConfirmPassword" placeholder="Confirm Password"
                name="password_confirmation" @error('password_confirmation') is-invalid @enderror required>
            <label for="floatingInputConfirmPassword">Confirm Password</label>
            @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <button type="submit" class="btn btn-outline-dark w-100">
            Reset Password
        </button>
        
    </form>

</div>
@endsection
