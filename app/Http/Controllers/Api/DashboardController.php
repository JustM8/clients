<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectStage;
use App\Models\ProjectStageItem;
use App\Models\ProjectStageWaitingLog;
use App\Models\ProjectStageWorkLog;
use App\Models\ProjectType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Показати сторінку Dashboard
     */
    public function index()
    {
        return view('admin.dashboard.index');
    }

    /**
     * GET /api/dashboard/overview
     * Загальна статистика
     */
    public function overview(): JsonResponse
    {
        $totalProjects = Project::count();
        $activeProjects = Project::where('status_id', 1)->count();
        $totalClients = User::where('role', 'client')->count();

        // Загальний час роботи в секундах
        $totalWorkSeconds = ProjectStageWorkLog::sum('duration_seconds');

        // Конвертуємо в години
        $totalWorkHours = round($totalWorkSeconds / 3600, 1);

        // Очікування клієнтів (статус running)
        $waitingCount = ProjectStageWaitingLog::where('status', 'running')->count();

        // Завершені проекти (можна адаптувати під вашу логіку)
        $completedProjects = Project::where('status_id', 6)->count(); // status_id = 6 (Запуск)

        // Загальна вартість проектів (сума rate * години)
        $totalRevenue = Project::sum(DB::raw('rate * (SELECT COALESCE(SUM(duration_seconds), 0) / 3600 FROM project_stage_work_logs WHERE project_stage_work_logs.project_id = projects.id)'));

        // Середній час на проект
        $avgTimePerProject = $totalProjects > 0
            ? round($totalWorkSeconds / $totalProjects / 3600, 1)
            : 0;

        return response()->json([
            'total_projects' => $totalProjects,
            'active_projects' => $activeProjects,
            'completed_projects' => $completedProjects,
            'total_clients' => $totalClients,
            'total_work_hours' => $totalWorkHours,
            'waiting_count' => $waitingCount,
            'total_revenue' => round($totalRevenue, 2),
            'avg_time_per_project' => $avgTimePerProject,
        ]);
    }

    /**
     * GET /api/dashboard/projects-by-type
     * Проекти по типах (для Pie Chart)
     */
    public function projectsByType(): JsonResponse
    {
        $data = Project::select('project_types.name as type_name', DB::raw('COUNT(projects.id) as count'))
            ->leftJoin('project_types', 'projects.type_id', '=', 'project_types.id')
            ->groupBy('projects.type_id', 'project_types.name')
            ->orderByDesc('count')
            ->get();

        // Формат для Chart.js
        $labels = $data->pluck('type_name')->map(fn($name) => $name ?? 'Без типу')->toArray();
        $values = $data->pluck('count')->toArray();

        // Кольори для графіка
        $colors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
            '#FF9F40', '#FF6384', '#C9CBCF', '#7BC225', '#E7E9ED',
            '#FF5733'
        ];

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $values,
                    'backgroundColor' => array_slice($colors, 0, count($labels)),
                    'borderWidth' => 2,
                    'borderColor' => '#1a1a2e',
                ]
            ],
            'raw_data' => $data,
        ]);
    }

    /**
     * GET /api/dashboard/projects-by-stage
     * Проекти по етапах (для Bar Chart)
     */
    public function projectsByStage(): JsonResponse
    {
        $stages = ProjectStage::orderBy('position')->get();

        $data = [];
        foreach ($stages as $stage) {
            // Рахуємо проекти, де поточний статус = цей етап
            $count = Project::where('status_id', $stage->id)->count();
            $data[] = [
                'stage_id' => $stage->id,
                'stage_name' => $stage->name,
                'count' => $count,
            ];
        }

        // Формат для Chart.js
        $labels = collect($data)->pluck('stage_name')->toArray();
        $values = collect($data)->pluck('count')->toArray();

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Кількість проєктів',
                    'data' => $values,
                    'backgroundColor' => '#FF6B35',
                    'borderColor' => '#FF6B35',
                    'borderWidth' => 1,
                    'borderRadius' => 8,
                ]
            ],
            'raw_data' => $data,
        ]);
    }

    /**
     * GET /api/dashboard/projects-by-month
     * Проекти по місяцях (для Line Chart)
     */
    public function projectsByMonth(): JsonResponse
    {
        // Останні 12 місяців
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();

        $data = Project::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', $startDate)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Генеруємо всі місяці за останній рік
        $labels = [];
        $values = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $year = $date->year;
            $month = $date->month;

            // Українські назви місяців
            $monthNames = [
                1 => 'Січ', 2 => 'Лют', 3 => 'Бер', 4 => 'Кві',
                5 => 'Тра', 6 => 'Чер', 7 => 'Лип', 8 => 'Сер',
                9 => 'Вер', 10 => 'Жов', 11 => 'Лис', 12 => 'Гру'
            ];

            $labels[] = $monthNames[$month] . ' ' . $year;

            $found = $data->first(fn($item) => $item->year == $year && $item->month == $month);
            $values[] = $found ? $found->count : 0;
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Нові проєкти',
                    'data' => $values,
                    'fill' => true,
                    'backgroundColor' => 'rgba(255, 107, 53, 0.2)',
                    'borderColor' => '#FF6B35',
                    'borderWidth' => 3,
                    'tension' => 0.4,
                    'pointBackgroundColor' => '#FF6B35',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 5,
                ]
            ],
        ]);
    }

    /**
     * GET /api/dashboard/work-hours-by-project
     * Час роботи по проєктах (для Bar Chart)
     */
    public function workHoursByProject(): JsonResponse
    {
        $data = Project::select(
            'projects.id',
            'projects.name',
            'projects.rate',
            DB::raw('COALESCE(SUM(project_stage_work_logs.duration_seconds), 0) as total_seconds')
        )
            ->leftJoin('project_stage_work_logs', 'projects.id', '=', 'project_stage_work_logs.project_id')
            ->groupBy('projects.id', 'projects.name', 'projects.rate')
            ->orderByDesc('total_seconds')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $hours = round($item->total_seconds / 3600, 2);
                $earnings = round($hours * $item->rate, 2);
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'hours' => $hours,
                    'minutes' => round($item->total_seconds / 60, 1),
                    'rate' => $item->rate,
                    'earnings' => $earnings,
                ];
            });

        // Формат для Chart.js
        $labels = $data->pluck('name')->toArray();
        $values = $data->pluck('hours')->toArray();

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Години роботи',
                    'data' => $values,
                    'backgroundColor' => '#4BC0C0',
                    'borderColor' => '#4BC0C0',
                    'borderWidth' => 1,
                    'borderRadius' => 8,
                ]
            ],
            'raw_data' => $data,
        ]);
    }

    /**
     * GET /api/dashboard/waiting-clients
     * Очікування клієнтів
     */
    public function waitingClients(): JsonResponse
    {
        $data = ProjectStageWaitingLog::select(
            'project_stage_waiting_logs.*',
            'projects.name as project_name',
            'project_stages.name as stage_name',
            'users.name as client_name',
            'admin.name as admin_name'
        )
            ->leftJoin('projects', 'project_stage_waiting_logs.project_id', '=', 'projects.id')
            ->leftJoin('project_stages', 'project_stage_waiting_logs.stage_id', '=', 'project_stages.id')
            ->leftJoin('users', 'projects.client_id', '=', 'users.id')
            ->leftJoin('users as admin', 'project_stage_waiting_logs.started_by_admin', '=', 'admin.id')
            ->orderByDesc('project_stage_waiting_logs.started_at')
            ->get()
            ->map(function ($item) {
                $startedAt = Carbon::parse($item->started_at);
                $daysWaiting = $item->status === 'running'
                    ? $startedAt->diffInDays(Carbon::now())
                    : null;

                $hoursWaiting = $item->status === 'running'
                    ? $startedAt->diffInHours(Carbon::now())
                    : null;

                return [
                    'id' => $item->id,
                    'project_id' => $item->project_id,
                    'project_name' => $item->project_name,
                    'stage_name' => $item->stage_name,
                    'client_name' => $item->client_name,
                    'admin_name' => $item->admin_name,
                    'admin_comment' => $item->admin_comment,
                    'client_comment' => $item->client_comment,
                    'status' => $item->status,
                    'started_at' => $item->started_at,
                    'client_stopped_at' => $item->client_stopped_at,
                    'days_waiting' => $daysWaiting,
                    'hours_waiting' => $hoursWaiting,
                    'is_urgent' => $daysWaiting > 2, // Більше 2 днів - терміново
                ];
            });

        // Статистика по статусах
        $stats = [
            'running' => $data->where('status', 'running')->count(),
            'pending' => $data->where('status', 'pending')->count(),
            'completed' => $data->where('status', 'completed')->count(),
            'urgent' => $data->where('is_urgent', true)->count(),
        ];

        return response()->json([
            'data' => $data,
            'stats' => $stats,
        ]);
    }

    /**
     * GET /api/dashboard/recent-activity
     * Остання активність
     */
    public function recentActivity(): JsonResponse
    {
        // Останні work logs
        $workLogs = ProjectStageWorkLog::select(
            'project_stage_work_logs.*',
            'projects.name as project_name',
            'project_stages.name as stage_name'
        )
            ->leftJoin('project_stage_items', 'project_stage_work_logs.stage_item_id', '=', 'project_stage_items.id')
            ->leftJoin('projects', 'project_stage_work_logs.project_id', '=', 'projects.id')
            ->leftJoin('project_stages', 'project_stage_items.stage_id', '=', 'project_stages.id')
            ->orderByDesc('project_stage_work_logs.created_at')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'work',
                    'icon' => '⏱️',
                    'message' => "Робота над \"{$item->project_name}\" - {$item->stage_name}",
                    'duration' => round($item->duration_seconds / 60, 1) . ' хв',
                    'created_at' => $item->created_at,
                    'date_formatted' => Carbon::parse($item->created_at)->format('d.m.Y H:i'),
                ];
            });

        // Останні проекти
        $recentProjects = Project::select('projects.*', 'users.name as client_name')
            ->leftJoin('users', 'projects.client_id', '=', 'users.id')
            ->orderByDesc('projects.created_at')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'project',
                    'icon' => '📁',
                    'message' => "Новий проєкт \"{$item->name}\" для {$item->client_name}",
                    'created_at' => $item->created_at,
                    'date_formatted' => Carbon::parse($item->created_at)->format('d.m.Y H:i'),
                ];
            });

        // Об'єднуємо та сортуємо
        $activity = $workLogs->concat($recentProjects)
            ->sortByDesc('created_at')
            ->values()
            ->take(15);

        return response()->json([
            'data' => $activity,
        ]);
    }
}
