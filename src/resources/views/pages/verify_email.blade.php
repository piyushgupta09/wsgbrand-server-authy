@extends('authy::layouts.main')

@section('content')
<div class="container w-100" style="max-width: 400px">

    <form method="POST" action="{{ route('verification.resend') }}">
        @csrf

        {{-- Form Title --}}
        <p class="text-center lead">Verify Your Email Address</p>

        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                A fresh verification link has been sent to your email address.
            </div>
        @endif

        <p class="p-2 mb-0">
            Before proceeding, please check your email for a verification link.
        </p>

        <p class="p-2 mb-0">
            If you did not receive the email, click the button below to request another.
        </p>

        {{-- Submit Button --}}
        <button type="submit" class="btn btn-outline-dark w-100 mt-4">
            Re-Send Email Verification Link
        </button>

    </form>
  
</div>
@endsection
