<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectStageTimer;
use Illuminate\Http\Request;

class ProjectStageTimerController extends Controller
{
    public function start(Project $project, Request $request)
    {
        $active = ProjectStageTimer::where('project_id', $project->id)
            ->whereNull('stopped_at')
            ->first();

        if ($active) {
            return response()->json(['error' => 'Таймер вже запущений'], 400);
        }

        $timer = ProjectStageTimer::create([
            'project_id'     => $project->id,
            'stage_item_id'  => $request->stage_item_id, // ← ПРАВИЛЬНО
            'started_at'     => now(),
        ]);

        return response()->json(['success' => true, 'timer' => $timer]);
    }

    public function stop(Project $project)
    {
        $active = ProjectStageTimer::where('project_id', $project->id)
            ->whereNull('stopped_at')
            ->first();

        if (!$active) {
            return response()->json(['error' => 'Немає активного таймера'], 404);
        }

        // Фіксуємо стоп
        $duration = now()->diffInSeconds($active->started_at);

        $active->update([
            'stopped_at' => now(),
            'duration_seconds' => $duration,
        ]);

        // 🔥 ОНОВЛЮЄМО ЕТАП
        $stageItem = $project->stageItems()
            ->where('id', $active->stage_item_id)
            ->first();

        if ($stageItem) {
            $stageItem->update([
                'spent_seconds' => $stageItem->spent_seconds + $duration
            ]);
        }

        return response()->json([
            'success' => true,
            'spent' => gmdate('H:i:s', $stageItem->spent_seconds ?? 0)
        ]);
    }


    public function status(Project $project)
    {
        // знаходимо останній запис по проекту
        $last = ProjectStageTimer::where('project_id', $project->id)
            ->orderBy('id', 'desc')
            ->first();

        // активний чи ні
        $running = $last && $last->stopped_at === null;

        return response()->json([
            'running' => $running,
            'timer' => $last,
            'stage_id' => $last?->stage_item_id,
        ]);
    }
}

