@extends('layouts.app')

@section('content')
<style>
    .verify-container {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .verify-card {
        background: rgba(42, 42, 42, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 72, 0, 0.2);
        border-radius: 24px;
        padding: 3rem;
        max-width: 600px;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        animation: fadeInScale 0.6s ease;
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .verify-icon {
        font-size: 4rem;
        margin-bottom: 1.5rem;
    }

    .verify-title {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #ff4800 0%, #ff6b00 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
    }

    .verify-text {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background: rgba(34, 197, 94, 0.2);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #22c55e;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .btn-link {
        color: #ff4800;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-link:hover {
        color: #ff6b00;
        text-decoration: underline;
    }
</style>

<div class="verify-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="verify-card">
                    <div class="verify-icon">📧</div>
                    <h1 class="verify-title">{{ __('Verify Your Email Address') }}</h1>

                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    <p class="verify-text">
                        {{ __('Before proceeding, please check your email for a verification link.') }}
                    </p>

                    <p class="verify-text">
                        {{ __('If you did not receive the email') }},
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link">
                                {{ __('click here to request another') }}
                            </button>
                        </form>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection