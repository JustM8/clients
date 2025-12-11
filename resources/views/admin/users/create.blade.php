@extends('layouts.app')

@section('content')
<style>
    .page-header {
        padding: 2rem 0;
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #ff4800 0%, #ff6b00 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .form-card {
        background: rgba(42, 42, 42, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 72, 0, 0.2);
        border-radius: 16px;
        padding: 2rem;
    }

    .form-label {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        background: rgba(13, 13, 13, 0.6);
        border: 1px solid rgba(255, 72, 0, 0.2);
        border-radius: 12px;
        color: #ffffff;
        padding: 0.875rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        background: rgba(13, 13, 13, 0.8);
        border-color: #ff4800;
        box-shadow: 0 0 0 0.2rem rgba(255, 72, 0, 0.2);
        color: #ffffff;
    }
</style>

<div class="container mt-4">
    <div class="page-header">
        <h1 class="page-title">Створити користувача</h1>
    </div>

    <div class="form-card">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Ім'я</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Пароль</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Компанія</label>
                <input type="text" name="company_name" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Телефон</label>
                <input type="text" name="phone" class="form-control">
            </div>

            <div class="d-flex gap-3 mt-4">
                <button class="btn btn-success">
                    ✅ Зберегти
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    ← Назад
                </a>
            </div>
        </form>
    </div>
</div>
@endsection