<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectStageWaitingLog;
use App\Models\ProjectStageWaitingMessage;
use App\Services\TelegramService;
use Illuminate\Http\Request;

class WaitingController extends Controller
{
    /**
     * ПІДТВЕРДИТИ відповідь клієнта
     */
    public function approve(ProjectStageWaitingLog $waiting)
    {
        // Скільки тривало очікування
        $duration = $waiting->started_at
            ? now()->diffInSeconds($waiting->started_at)
            : 0;

        // Оновлюємо лог очікування
        $waiting->update([
            'status'           => 'approved',      // або 'completed', якщо тобі так більше подобається
            'stopped_at'       => now(),
            'duration_seconds' => $duration,
        ]);

        // Пишемо в історію
        ProjectStageWaitingMessage::create([
            'waiting_log_id' => $waiting->id,
            'from'           => 'admin',
            'message'        => 'Менеджер підтвердив відповідь клієнта.',
        ]);

        // Закриваємо поточний етап (якщо він ще відкритий)
        $stageItem = $waiting->project->stageItems()
            ->where('stage_id', $waiting->stage_id)
            ->whereNull('end_date')
            ->first();

        if ($stageItem) {
            $seconds = now()->diffInSeconds($stageItem->start_date);
            $stageItem->update([
                'end_date'      => now(),
                'spent_seconds' => $seconds,
            ]);
        }

        // Telegram (опціонально)
        app(TelegramService::class)->sendMessage(
            "✅ <b>Менеджер підтвердив у адмін-панелі.</b>",
            $waiting->project->telegram_thread_id
        );

        return back()->with('success', 'Підтверджено!');
    }

    /**
     * ВІДХИЛИТИ відповідь клієнта
     */
    public function reject(ProjectStageWaitingLog $waiting, Request $request)
    {
        $reason = $request->input('reason'); // якщо захочеш додати поле з причиною

        $waiting->update([
            'status'                => 'rejected',
            'rejected_by_admin_at'  => now(),
            'rejected_admin_comment'=> $reason,
        ]);

        // Пишемо в історію
        ProjectStageWaitingMessage::create([
            'waiting_log_id' => $waiting->id,
            'from'           => 'admin',
            'message'        => $reason
                ? "Менеджер відхилив відповідь клієнта. Причина: {$reason}"
                : "Менеджер відхилив відповідь клієнта.",
        ]);

        // Telegram
        app(TelegramService::class)->sendMessage(
            "❌ Менеджер відхилив у адмін-панелі.",
            $waiting->project->telegram_thread_id
        );

        return back()->with('success', 'Відхилено!');
    }
}
