@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
    @vite(['resources/sass/admin-dashboard.scss'])
@endpush

@section('content')
    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">📊 Dashboard</h1>
            <p class="dashboard-subtitle">Ласкаво просимо! Ось огляд ваших проєктів.</p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid" id="stats-grid">
            <!-- Loading skeletons -->
            @for ($i = 0; $i < 6; $i++)
                <div class="stat-card">
                    <div class="loading-skeleton" style="width: 48px; height: 48px; margin-bottom: 16px;"></div>
                    <div class="loading-skeleton" style="width: 80px; height: 36px; margin-bottom: 8px;"></div>
                    <div class="loading-skeleton" style="width: 120px; height: 14px;"></div>
                </div>
            @endfor
        </div>

        <!-- Charts Row 1 -->
        <div class="charts-grid">
            <!-- Projects by Type - Pie Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">🥧 Проєкти по типах</h3>
                </div>
                <div class="chart-container">
                    <canvas id="projectsByTypeChart"></canvas>
                </div>
            </div>

            <!-- Projects by Stage - Bar Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">📊 Проєкти по етапах</h3>
                </div>
                <div class="chart-container">
                    <canvas id="projectsByStageChart"></canvas>
                </div>
            </div>

            <!-- Projects by Month - Line Chart -->
            <div class="chart-card full-width">
                <div class="chart-header">
                    <h3 class="chart-title">📈 Динаміка проєктів <span>за останні 12 місяців</span></h3>
                </div>
                <div class="chart-container">
                    <canvas id="projectsByMonthChart"></canvas>
                </div>
            </div>

            <!-- Work Hours by Project -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">⏱️ Години роботи по проєктах</h3>
                </div>
                <div class="chart-container">
                    <canvas id="workHoursChart"></canvas>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">🔔 Остання активність</h3>
                </div>
                <div class="activity-feed" id="activity-feed">
                    <div class="activity-item">
                        <div class="loading-skeleton" style="width: 40px; height: 40px; border-radius: 10px;"></div>
                        <div style="flex: 1;">
                            <div class="loading-skeleton" style="width: 100%; height: 16px; margin-bottom: 8px;"></div>
                            <div class="loading-skeleton" style="width: 60%; height: 12px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Waiting Clients Table -->
        <div class="chart-card full-width">
            <div class="chart-header">
                <h3 class="chart-title">
                    🚨 Очікування клієнтів
                    <span id="waiting-stats"></span>
                </h3>
            </div>
            <div style="overflow-x: auto;">
                <table class="waiting-table" id="waiting-table">
                    <thead>
                    <tr>
                        <th>Проєкт</th>
                        <th>Клієнт</th>
                        <th>Етап</th>
                        <th>Коментар</th>
                        <th>Статус</th>
                        <th>Очікування</th>
                    </tr>
                    </thead>
                    <tbody id="waiting-tbody">
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px;">
                            <div class="loading-skeleton" style="width: 200px; height: 20px; margin: 0 auto;"></div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/admin-dashboard.js')
@endpush


