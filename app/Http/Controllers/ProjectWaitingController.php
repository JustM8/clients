<?php

namespace App\Http\Controllers;

use App\Mail\ProjectStageNotification;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\ProjectStageWaitingLog;
use App\Models\ProjectStageWaitingMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProjectWaitingController extends Controller
{
    /**
     * Адмін запускає очікування
     */
    public function start(Request $request, Project $project)
    {
        $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        // вже запущено?
        $active = ProjectStageWaitingLog::where('project_id', $project->id)
            ->where('status', 'running')
            ->first();

        if ($active) {
            return response()->json(['error' => 'Уже запущений таймер очікування'], 422);
        }

        // 🟢 ВАЖЛИВО: правильний етап!
        $stageId = $project->status_id;

        $log = ProjectStageWaitingLog::create([
            'project_id'       => $project->id,
            'stage_id'         => $stageId,   // ← FIXED
            'started_at'       => now(),
            'started_by_admin' => auth()->id(),
            'admin_comment'    => $request->comment,
            'status'           => 'running',
        ]);

        // Історія
        ProjectStageWaitingMessage::create([
            'waiting_log_id' => $log->id,
            'from'           => 'admin',
            'message'        => $request->comment,
        ]);

        // Email для клієнта
        $project->load(['client', 'status']);

        if ($project->client?->email) {
            try {
                Mail::to($project->client->email)
                    ->send(new ProjectStageNotification($project));
            } catch (\Exception $e) {
                \Log::error('Помилка при надсиланні листа: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success'     => true,
            'log_id'      => $log->id,
            'started_at'  => $log->started_at->toISOString(),
        ]);
    }


    /**
     * Клієнт зупиняє / відповідає
     */
    public function clientStop(Request $request, Project $project)
    {
        abort_if($project->client_id !== Auth::id(), 403);

        $request->validate([
            'comment' => 'required|string',
        ]);

        // беремо ОСТАННІЙ лог (а не лише running)
        $waiting = $project->waiting()->latest()->first();

        if (!$waiting) {
            return back()->with('error', 'Етап не знайдено');
        }

        // клієнт може відповідати лише при running або rejected
        if (!in_array($waiting->status, ['running', 'rejected'])) {
            return back()->with('error', 'Зараз відповідати неможливо');
        }

        $waiting->update([
            'client_comment'    => $request->comment,
            'client_stopped_at' => now(),
            'status'            => 'pending',
        ]);

        // Історія
        ProjectStageWaitingMessage::create([
            'waiting_log_id' => $waiting->id,
            'from'           => 'client',
            'message'        => $request->comment,
        ]);

        // Telegram
        $telegram = app(\App\Services\TelegramService::class);

        $telegram->sendProjectMessage(
            "📩 Клієнт надав інформацію:\n\n{$request->comment}",
            $project->telegram_thread_id,
            false
        );

        $telegram->sendWaitingForApprove(
            $waiting->id,
            $request->comment,
            $project->telegram_thread_id
        );

        return back()->with('success', 'Інформацію надіслано');
    }


    /**
     * CRON: відправляємо нагадування раз на добу
     */
    public function sendDailyNotifications()
    {
        $waitings = ProjectStageWaitingLog::where('status', 'running')
            ->where(function ($q) {
                $q->whereNull('last_notification_at')
                    ->orWhere('last_notification_at', '<', now()->subHours(24));
            })
            ->get();

        foreach ($waitings as $waiting) {

            $project = $waiting->project()->with(['client', 'status'])->first();

            if (!$project?->client?->email) continue;

            try {
                Mail::to($project->client->email)
                    ->send(new ProjectStageNotification($project));

                $waiting->update([
                    'last_notification_at' => now(),
                ]);

            } catch (\Exception $e) {
                \Log::error('Cron email error: '.$e->getMessage());
            }
        }
    }
}
