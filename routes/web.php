<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WaitingController;
use App\Http\Controllers\ProjectStageTimerController;
use App\Http\Controllers\ProjectTimerController;
use App\Http\Controllers\ProjectWaitingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/locale/{lang}', [\App\Http\Controllers\LanguageController::class,'index'])->name('index');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'admin'])->prefix('api/dashboard')->name('api.dashboard.')->group(function () {
    Route::get('/overview', [DashboardController::class, 'overview'])->name('overview');
    Route::get('/projects-by-type', [DashboardController::class, 'projectsByType'])->name('projects-by-type');
    Route::get('/projects-by-stage', [DashboardController::class, 'projectsByStage'])->name('projects-by-stage');
    Route::get('/projects-by-month', [DashboardController::class, 'projectsByMonth'])->name('projects-by-month');
    Route::get('/work-hours-by-project', [DashboardController::class, 'workHoursByProject'])->name('work-hours-by-project');
    Route::get('/waiting-clients', [DashboardController::class, 'waitingClients'])->name('waiting-clients');
    Route::get('/recent-activity', [DashboardController::class, 'recentActivity'])->name('recent-activity');
});

// 🔹 Адмінська зона
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // Користувачі
        Route::resource('users', UserController::class)->names('admin.users');

        // Проєкти
        Route::resource('projects', ProjectController::class)->names('admin.projects');
        Route::post('/projects/{project}/message', [ProjectController::class, 'sendMessage'])
            ->name('project.message.send');

//        // 🔹 Таймер — тільки для адміна
//        Route::post('/projects/{project}/timer/start', [ProjectTimerController::class, 'start'])
//            ->name('admin.timer.start');
//        Route::post('/projects/{project}/timer/stop', [ProjectTimerController::class, 'stop'])
//            ->name('admin.timer.stop');

        Route::post('/projects/{project}/stage/add', [ProjectController::class, 'addStage'])
            ->name('admin.projects.stage.add');

        Route::post('/projects/{project}/timer/start', [ProjectStageTimerController::class, 'start'])
            ->name('project.timer.start');

        Route::post('/projects/{project}/timer/stop', [ProjectStageTimerController::class, 'stop'])
            ->name('project.timer.stop');

        Route::get('/projects/{project}/timer/status', [ProjectStageTimerController::class, 'status'])
            ->name('project.timer.status');

        Route::post('/projects/{project}/waiting/start', [ProjectWaitingController::class, 'start'])
            ->name('project.waiting.start');



        Route::post('/admin/waiting/{waiting}/approve', [WaitingController::class, 'approve'])
            ->name('admin.waiting.approve');

        Route::post('/admin/waiting/{waiting}/reject', [WaitingController::class, 'reject'])
            ->name('admin.waiting.reject');



    });

// 🔹 Кабінет клієнта
Route::middleware(['auth', 'client'])->group(function () {
    Route::get('/projects', [App\Http\Controllers\Client\ProjectController::class, 'index'])
        ->name('client.projects.index');
    Route::get('/projects/{project}', [App\Http\Controllers\Client\ProjectController::class, 'show'])
        ->name('client.projects.show');
    Route::post('/projects/{project}/message', [App\Http\Controllers\Client\ProjectController::class, 'sendMessage'])
        ->name('client.projects.message');
    Route::post('/project/{project}/waiting/stop-client',
        [ProjectWaitingController::class, 'clientStop']
    )->name('project.waiting.stop.client');
    Route::get('/projects/{project}/waiting/status', [\App\Http\Controllers\Client\ProjectController::class, 'waitingStatus'])
        ->name('project.waiting.status');

});

// 🔹 Тест пошти
Route::get('/test-mail', function () {
    $project = \App\Models\Project::first();
    \Mail::to('gav.sqrt@gmail.com')->send(new \App\Mail\ProjectStageNotification($project));
    return '✅ Лист надіслано';
});
