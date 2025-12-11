@extends('layouts.app')

@section('content')
<style>
    .welcome-section {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at 30% 50%, rgba(255, 72, 0, 0.1) 0%, transparent 50%);
    }

    .welcome-card {
        background: rgba(42, 42, 42, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 72, 0, 0.2);
        border-radius: 24px;
        padding: 4rem;
        max-width: 700px;
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

    .welcome-title {
        font-size: 3rem;
        font-weight: 800;
        background: linear-gradient(135deg, #ff4800 0%, #ff6b00 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
    }

    .welcome-subtitle {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 400;
        margin-bottom: 2rem;
    }

    .success-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2rem;
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        border-radius: 50px;
        color: #22c55e;
        font-weight: 600;
    }

    .status-indicator {
        width: 10px;
        height: 10px;
        background: #22c55e;
        border-radius: 50%;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
</style>

<div class="welcome-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="welcome-card">
                    <h1 class="welcome-title">{{ __('Dashboard') }}</h1>
                    <p class="welcome-subtitle">Welcome to Smarto Agency Client Portal</p>

                    @if (session('status'))
                        <div class="success-badge">
                            <span class="status-indicator"></span>
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="success-badge mt-4">
                        <span class="status-indicator"></span>
                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection