import Chart from "chart.js/auto";

// === On load ===
document.addEventListener('DOMContentLoaded', function() {
    // Chart.js глобальні налаштування
    Chart.defaults.color = '#8a8a9a';
    Chart.defaults.borderColor = '#2a2a4a';
    Chart.defaults.font.family = "'Inter', sans-serif";

    // Завантажуємо всі дані
    loadOverview();
    loadProjectsByType();
    loadProjectsByStage();
    loadProjectsByMonth();
    loadWorkHours();
    loadWaitingClients();
    loadRecentActivity();
});

// =========================
// 1. Overview Stats
// =========================
async function loadOverview() {
    try {
        const response = await fetch('/api/dashboard/overview');
        const data = await response.json();

        const statsGrid = document.getElementById('stats-grid');
        statsGrid.innerHTML = `
            <div class="stat-card">
                <div class="stat-icon">📁</div>
                <div class="stat-value">${data.total_projects}</div>
                <div class="stat-label">Всього проєктів</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⚡</div>
                <div class="stat-value">${data.active_projects}</div>
                <div class="stat-label">Активні проєкти</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-value">${data.completed_projects}</div>
                <div class="stat-label">Завершені</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-value">${data.total_clients}</div>
                <div class="stat-label">Клієнтів</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⏱️</div>
                <div class="stat-value">${data.total_work_hours}</div>
                <div class="stat-label">Годин роботи</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🔔</div>
                <div class="stat-value">${data.waiting_count}</div>
                <div class="stat-label">Очікують відповіді</div>
                ${data.waiting_count > 0 ? '<span class="stat-change negative">потребує уваги</span>' : ''}
            </div>
        `;
    } catch (error) {
        console.error('Error loading overview:', error);
    }
}

// =========================
// 2. Projects by Type
// =========================
async function loadProjectsByType() {
    try {
        const response = await fetch('/api/dashboard/projects-by-type');
        const data = await response.json();

        const ctx = document.getElementById('projectsByTypeChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: data.datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                },
                cutout: '60%'
            }
        });
    } catch (error) {
        console.error('Error loading projects by type:', error);
    }
}

// =========================
// 3. Projects by Stage
// =========================
async function loadProjectsByStage() {
    try {
        const response = await fetch('/api/dashboard/projects-by-stage');
        const data = await response.json();

        const ctx = document.getElementById('projectsByStageChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: data.datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: { legend: { display: false }},
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: '#2a2a4a' },
                        ticks: { stepSize: 1 }
                    },
                    y: {
                        grid: { display: false }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error loading projects by stage:', error);
    }
}

// =========================
// 4. Projects by Month
// =========================
async function loadProjectsByMonth() {
    try {
        const response = await fetch('/api/dashboard/projects-by-month');
        const data = await response.json();

        const ctx = document.getElementById('projectsByMonthChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: data.datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }},
                scales: {
                    x: { grid: { color: '#2a2a4a' }},
                    y: {
                        beginAtZero: true,
                        grid: { color: '#2a2a4a' },
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error loading projects by month:', error);
    }
}

// =========================
// 5. Work Hours Chart
// =========================
async function loadWorkHours() {
    try {
        const response = await fetch('/api/dashboard/work-hours-by-project');
        const data = await response.json();

        const ctx = document.getElementById('workHoursChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: data.datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }},
                scales: {
                    x: { grid: { display: false }},
                    y: {
                        beginAtZero: true,
                        grid: { color: '#2a2a4a' }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error loading work hours:', error);
    }
}

// =========================
// 6. Waiting Clients
// =========================
async function loadWaitingClients() {
    try {
        const response = await fetch('/api/dashboard/waiting-clients');
        const result = await response.json();

        document.getElementById('waiting-stats').innerHTML =
            `<span style="color: #FFCE56;">${result.stats.running} активних</span> ·
             <span style="color: #36A2EB;">${result.stats.pending} очікують</span>
             ${result.stats.urgent > 0 ? ` · <span style="color: #FF6384;">${result.stats.urgent} терміново!</span>` : ''}`;

        const tbody = document.getElementById('waiting-tbody');

        if (result.data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: #4BC0C0;">
                        ✅ Немає очікувань клієнтів
                    </td>
                </tr>`;
            return;
        }

        tbody.innerHTML = result.data.map(item => `
            <tr>
                <td><strong>${item.project_name}</strong></td>
                <td>${item.client_name || '-'}</td>
                <td>${item.stage_name || '-'}</td>
                <td style="max-width: 200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                    ${item.admin_comment || '-'}
                </td>
                <td>
                    <span class="status-badge ${item.status} ${item.is_urgent ? 'urgent' : ''}">
                        ${item.status === 'running' ? '🔄 Активний'
            : item.status === 'pending' ? '⏳ Очікує'
                : '✅ Завершено'}
                    </span>
                </td>
                <td>
                    ${item.days_waiting !== null ? `
                        <strong>${item.days_waiting}</strong> днів
                        <div class="days-badge">${item.hours_waiting} годин</div>
                    ` : '-'}
                </td>
            </tr>
        `).join('');
    } catch (error) {
        console.error('Error loading waiting clients:', error);
    }
}

// =========================
// 7. Recent Activity
// =========================
async function loadRecentActivity() {
    try {
        const response = await fetch('/api/dashboard/recent-activity');
        const result = await response.json();

        const feed = document.getElementById('activity-feed');

        if (result.data.length === 0) {
            feed.innerHTML = `
                <div style="text-align: center; padding: 40px; color: #8a8a9a;">
                    Немає активності
                </div>`;
            return;
        }

        feed.innerHTML = result.data.map(item => `
            <div class="activity-item">
                <div class="activity-icon">${item.icon}</div>
                <div class="activity-content">
                    <div class="activity-message">${item.message}</div>
                    <div class="activity-time">
                        ${item.date_formatted}
                        ${item.duration ? `<span class="activity-duration"> · ${item.duration}</span>` : ''}
                    </div>
                </div>
            </div>
        `).join('');
    } catch (error) {
        console.error('Error loading activity:', error);
    }
}
