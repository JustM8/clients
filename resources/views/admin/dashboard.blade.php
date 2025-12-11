@extends('layouts.app')

@section('content')
<style>
    .dashboard-hero {
        padding: 3rem 0;
        background: radial-gradient(circle at 30% 50%, rgba(255, 72, 0, 0.1) 0%, transparent 50%);
    }

    .dashboard-title {
        font-size: 3rem;
        font-weight: 800;
        background: linear-gradient(135deg, #ff4800 0%, #ff6b00 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .dashboard-subtitle {
        color: rgba(255, 255, 255, 0.6);
        font-size: 1.2rem;
    }

    .stat-card {
        background: rgba(42, 42, 42, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 72, 0, 0.2);
        border-radius: 16px;
        padding: 2rem;
        transition: all 0.3s ease;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        border-color: rgba(255, 72, 0, 0.5);
        box-shadow: 0 10px 30px rgba(255, 72, 0, 0.2);
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #ff4800;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: rgba(255, 255, 255, 0.7);
        font-size: 1rem;
    }
</style>

<div class="dashboard-hero">
    <div class="container">
        <h1 class="dashboard-title">Admin Dashboard</h1>
        <p class="dashboard-subtitle">Welcome back, {{ Auth::user()->name }}!</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon">🏗️</div>
                <div class="stat-value">{{ \App\Models\Project::count() }}</div>
                <div class="stat-label">Total Projects</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-value">{{ \App\Models\User::where('role', 'client')->count() }}</div>
                <div class="stat-label">Clients</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon">⚡</div>
                <div class="stat-value">{{ \App\Models\Project::whereNotNull('status_id')->count() }}</div>
                <div class="stat-label">Active Projects</div>
            </div>
        </div>
    </div>
</div>
@endsection