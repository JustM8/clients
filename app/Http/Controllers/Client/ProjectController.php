<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectStageItem;
use App\Models\ProjectStageWaitingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\TelegramService;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::where('client_id', Auth::id())
            ->with('status')
            ->latest()
            ->get();

        return view('client.projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        abort_if($project->client_id !== Auth::id(), 403);


        // Всі етапи
        $stageItems = ProjectStageItem::where('project_id', $project->id)
            ->with('stage')
            ->orderBy('position')
            ->get();

        // Поточний етап
        $currentStage = ProjectStageItem::where('project_id', $project->id)
            ->whereNull('end_date')
            ->orderBy('position')
            ->first();

        // Активний таймер
        $currentStageId = $project->status_id;

        $waitingActive = ProjectStageWaitingLog::where('project_id', $project->id)
            ->where('stage_id', $currentStageId)   // ← ВАЖЛИВО!
            ->whereIn('status', ['running', 'pending', 'rejected'])
            ->latest()
            ->first();

        // === Робочі години: обчислення bufferEnd ===
        if ($waitingActive) {
            $start = $waitingActive->started_at;
            $hours = $project->buffer_hours ?? 48;

            $bufferEnd = addWorkingHours($start, $hours);

            $now = now();
            $usedSeconds = workingSecondsBetween($start, $now);

            $bufferSeconds = $hours * 3600;

            // Скільки залишилось безкоштовних
            $freeLeftSec = max(0, $bufferSeconds - $usedSeconds);

            // Якщо буфер вичерпано → платний час
            $paidSec = $usedSeconds > $bufferSeconds
                ? $usedSeconds - $bufferSeconds
                : 0;
        } else {
            $bufferEnd = null;
            $freeLeftSec = null;
            $paidSec = null;
        }



        // 🟩 ІСТОРІЯ ВСІХ ОЧІКУВАНЬ
        $waitingHistory = ProjectStageWaitingLog::where('project_id', $project->id)
            ->with('messages')
            ->orderByDesc('id')
            ->get();


        // Чат
        $project->load('messages.user', 'status');


        return view('client.projects.show', [
            'project'       => $project,
            'stageItems'    => $stageItems,
            'currentStage'  => $currentStage,
            'waitingActive' => $waitingActive,
            'waitingHistory'=> $waitingHistory,
            'bufferEnd'     => $bufferEnd,
            'freeLeftSec'   => $freeLeftSec,
            'paidSec'       => $paidSec,
        ]);

    }



    public function sendMessage(Request $request, Project $project)
    {
        abort_if($project->client_id !== Auth::id(), 403);

        $data = $request->validate(['message' => 'required|string']);

        $message = $project->messages()->create([
            'user_id' => Auth::id(),
            'message' => $data['message'],
            'from_client' => true,
        ]);

        app(TelegramService::class)
            ->sendProjectMessage($message->message, $project->telegram_thread_id, true);

        return back()->with('success', 'Повідомлення надіслано');
    }

    public function waitingStatus(Project $project)
    {
        $waiting = $project->waiting()->latest()->first();

        if (!$waiting) {
            return response()->json(['status' => 'none']);
        }

        return response()->json([
            'status'         => $waiting->status,
            'admin_comment'  => $waiting->admin_comment,
            'client_comment' => $waiting->client_comment,
            'started_at'     => $waiting->started_at?->timestamp,
            'completed_at'   => $waiting->completed_at?->timestamp,
        ]);
    }
}
