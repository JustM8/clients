<?php

namespace App\Console;

use App\Mail\ProjectStageNotification;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $activeTimers = \App\Models\ProjectTimer::where('active', true)->get();

            foreach ($activeTimers as $timer) {
                if (!$timer->last_notification_sent_at || now()->diffInHours($timer->last_notification_sent_at) >= 24) {
                    Mail::to($timer->project->client->email)
                        ->send(new ProjectStageNotification($timer->project));

                    $timer->update(['last_notification_sent_at' => now()]);
                }
            }
        })->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
