<?php

namespace App\Http\Controllers;

use App\Mail\ProjectStageNotification;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProjectTimerController extends Controller
{
    public function start(Project $project)
    {
        // Гарантуємо, що підвантажимо зв’язки для пошти
        $project->load(['client', 'status']);

        // Створюємо таймер і передаємо користувача
        $project->timers()->create([
            'user_id' => auth()->id(),
            'started_at' => now(),
            'active' => true,
        ]);

        // Безпечна перевірка на наявність email клієнта
        if ($project->client && $project->client->email) {
            try {
                Mail::to($project->client->email)
                    ->send(new ProjectStageNotification($project));
            } catch (\Exception $e) {
                \Log::error('Помилка при надсиланні листа: ' . $e->getMessage());
            }
        }

        return response()->json(['success' => true]);
    }

    public function stop(Project $project)
    {
        $active = $project->timers()
            ->where('active', true)
            ->latest()
            ->first();

        if ($active) {
            $active->stop(); // викликає внутрішній розрахунок duration
            $active->active = false;
            $active->save();
        }

        return response()->json(['success' => true]);
    }
}
