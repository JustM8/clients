<?php

namespace App\Http\Controllers;

use App\Mail\WaitingReminder;
use App\Models\ProjectStageWaitingLog;
use Illuminate\Support\Facades\Mail;

class CronController extends Controller
{
    public function sendWaitingReminders()
    {
        // Беремо всі активні очікування
        $waitings = ProjectStageWaitingLog::where('status', 'running')
            ->where('started_at', '<=', now()->subDay()) // більше доби
            ->get();

        foreach ($waitings as $w) {

            $project = $w->project;

            if (!$project?->client?->email) {
                continue;
            }

            // Анти-дубль
            if ($w->last_notification_at &&
                $w->last_notification_at > now()->subDay()) {
                continue;
            }

            // Відправляємо лист
            Mail::to($project->client->email)
                ->send(new WaitingReminder($project));

            // Оновлюємо час останнього нагадування
            $w->update([
                'last_notification_at' => now()
            ]);
        }

        return "OK";
    }
}
