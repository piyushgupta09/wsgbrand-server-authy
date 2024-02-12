@extends('authy::layouts.main')

@section('content')
<div class="container w-100" style="max-width: 400px">
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        {{-- Form Title --}}
        <p class="text-center lead">Confirm Password</p>

        <p class="p-2 mb-0">
            Before proceeding, please confirm your password.
        </p>
        
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
            Confirm Password
        </button>

    </form>

    @if (Route::has('password.request'))
        <a class="btn btn-link p-0 mt-4" href="{{ route('password.request') }}">
            {{ __('Forgot Your Password?') }}
        </a>
    @endif

</div>
@endsection
