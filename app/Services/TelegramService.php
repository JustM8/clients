<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $botToken;
    protected string $chatId;

    public function __construct()
    {
        // ✅ Надійно читаємо токен і групу навіть якщо config кешовано
        $this->botToken = config('services.telegram.bot_token', env('TELEGRAM_BOT_TOKEN'));
        $this->chatId   = config('services.telegram.group_id', env('TELEGRAM_GROUP_ID'));
    }

    /**
     * 🧵 Створює новий тред (forum topic) для проєкту
     */
    public function createThread(string $title): ?int
    {
        try {
            $response = Http::post("https://api.telegram.org/bot{$this->botToken}/createForumTopic", [
                'chat_id' => $this->chatId,
                'name'    => mb_substr($title, 0, 128),
            ]);

            Log::info('📩 Telegram createForumTopic response', $response->json());

            if ($response->successful()) {
                return $response->json('result.message_thread_id');
            }

            Log::warning('⚠️ createThread failed', [
                'title' => $title,
                'response' => $response->json(),
            ]);
        } catch (\Throwable $e) {
            Log::error('❌ Telegram createThread exception', ['error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * 💬 Відправляє повідомлення у вказаний тред або просто у групу
     */
    public function sendMessage(string $text, ?int $threadId = null, array $replyMarkup = null): bool
    {
        $payload = [
            'chat_id' => $this->chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        if ($threadId) {
            $payload['message_thread_id'] = $threadId;
        }

        if ($replyMarkup) {
            $payload['reply_markup'] = json_encode($replyMarkup);
        }

        try {
            $response = Http::post("https://api.telegram.org/bot{$this->botToken}/sendMessage", $payload);
            Log::info('📨 Telegram sendMessage response', $response->json());
            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('❌ Telegram sendMessage exception', ['error' => $e->getMessage()]);
            return false;
        }
    }


    /**
     * 🧩 Форматоване повідомлення для проєкту
     */
    public function sendProjectMessage(string $text, ?int $threadId = null, bool $fromClient = true): bool
    {
        if (!$threadId) {
            Log::warning('⚠️ sendProjectMessage skipped — threadId is null');
            return false;
        }

        $prefix = $fromClient ? '👤 <b>Клієнт:</b>' : '🧑‍💼 <b>Адмін:</b>';
        return $this->sendMessage("{$prefix}\n{$text}", $threadId);
    }

    public function sendWaitingForApprove(int $waitingId, string $comment, int $threadId): bool
    {
        $payload = [
            'chat_id' => $this->chatId,
            'message_thread_id' => $threadId,
            'text' => "⏳ Очікується підтвердження етапу від менеджера.\n\nКоментар клієнта:\n{$comment}",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => '✅ Підтвердити', 'callback_data' => "waiting_approve_{$waitingId}"],
                        ['text' => '❌ Відхилити',  'callback_data' => "waiting_reject_{$waitingId}"],
                    ]
                ]
            ]),
            'parse_mode' => 'HTML',
        ];

        try {
            $response = Http::post("https://api.telegram.org/bot{$this->botToken}/sendMessage", $payload);

            Log::info("📨 Telegram approve buttons", $response->json());

            return $response->successful();

        } catch (\Throwable $e) {
            Log::error("❌ sendWaitingForApprove exception", ['error' => $e->getMessage()]);
            return false;
        }
    }


}
