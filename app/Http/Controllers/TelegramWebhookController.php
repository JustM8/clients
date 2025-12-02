<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Project;
use App\Models\ProjectMessage;
use App\Models\ProjectStageWaitingLog;
use App\Models\ProjectStageWaitingMessage;
use App\Services\TelegramService;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();
        Log::info('Telegram webhook:', $data);

        //
        // 🔥 CALLBACK (кнопки)
        //
        if (isset($data['callback_query'])) {

            $cb          = $data['callback_query'];
            $callbackId  = $cb['id'];
            $callbackData = $cb['data'];
            $threadId    = $cb['message']['message_thread_id'];

            // APPROVE
            if (str_starts_with($callbackData, "waiting_approve_")) {
                $id = (int) str_replace("waiting_approve_", "", $callbackData);
                $this->answerCallback($callbackId, "Підтверджено!");
                return $this->approveWaiting($id, $threadId);
            }

            // REJECT
            if (str_starts_with($callbackData, "waiting_reject_")) {
                $id = (int) str_replace("waiting_reject_", "", $callbackData);
                $this->answerCallback($callbackId, "Відхилено!");
                return $this->rejectWaiting($id, $threadId);
            }

            return response()->json(['ok' => true]);
        }


        //
        // 🔥 Текстове повідомлення в тред
        //
        if (!isset($data['message']['message_thread_id']) || !isset($data['message']['text'])) {
            return response()->noContent();
        }

        $threadId = $data['message']['message_thread_id'];
        $text     = $data['message']['text'];

        $project = Project::where('telegram_thread_id', $threadId)->first();

        if ($project) {
            ProjectMessage::create([
                'project_id'  => $project->id,
                'user_id'     => null,
                'message'     => $text,
                'from_client' => false,
            ]);
        }

        return response()->json(['ok' => true]);
    }


    // ---------------------------------------------------------------------
    // APPROVE
    // ---------------------------------------------------------------------
    private function approveWaiting(int $id, int $threadId)
    {
        $waiting = ProjectStageWaitingLog::find($id);

        if (!$waiting) {
            return $this->answerBot($threadId, "❌ Не знайдено запису очікування");
        }

        $waiting->update([
            'status' => 'approved',
            'manager_approved_at' => now(),
        ]);

        ProjectStageWaitingMessage::create([
            'waiting_log_id' => $waiting->id,
            'from'           => 'admin',
            'message'        => 'Менеджер підтвердив етап',
        ]);

        $project = $waiting->project;

        $stageItem = $project->stageItems()
            ->where('stage_id', $waiting->stage_id)
            ->whereNull('end_date')
            ->first();

        if ($stageItem) {
            $seconds = now()->diffInSeconds($stageItem->start_date);

            $stageItem->update([
                'end_date'      => now(),
                'spent_seconds' => $seconds
            ]);
        }

        app(TelegramService::class)->sendMessage(
            "✅ <b>Менеджер підтвердив.</b>\nЕтап зупинено та зафіксовано у системі.",
            $threadId
        );

        return response()->json(['ok' => true]);
    }


    // ---------------------------------------------------------------------
    // REJECT
    // ---------------------------------------------------------------------
    private function rejectWaiting(int $id, int $threadId)
    {
        $waiting = ProjectStageWaitingLog::find($id);

        if (!$waiting) {
            return $this->answerBot($threadId, "❌ Не знайдено запису очікування");
        }

        $waiting->update([
            'status' => 'rejected',
            'manager_rejected_at' => now(),
        ]);

        ProjectStageWaitingMessage::create([
            'waiting_log_id' => $waiting->id,
            'from'           => 'admin',
            'message'        => 'Менеджер повернув етап на доопрацювання',
        ]);

        app(TelegramService::class)->sendMessage(
            "❌ Менеджер повернув етап на доопрацювання. Клієнт може внести зміни.",
            $threadId
        );

        return response()->json(['ok' => true]);
    }



    // ---------------------------------------------------------------------
    // Відправка повідомлення від бота
    // ---------------------------------------------------------------------
    private function answerBot(int $threadId, string $text)
    {
        app(TelegramService::class)->sendMessage($text, $threadId);
        return response()->json(['ok' => true]);
    }


    // ---------------------------------------------------------------------
    // Обов'язково! Відповідь на callbackQuery
    // ---------------------------------------------------------------------
    private function answerCallback(string $callbackId, string $text): void
    {
        $token = config('services.telegram.bot_token');

        file_get_contents(
            "https://api.telegram.org/bot{$token}/answerCallbackQuery?callback_query_id={$callbackId}&text=" . urlencode($text)
        );
    }
}
