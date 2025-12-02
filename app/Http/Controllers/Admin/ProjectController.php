<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectStage;
use App\Models\ProjectStageItem;
use App\Models\ProjectStageWaitingLog;
use App\Models\ProjectStatus;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Auth;
use App\Models\ProjectType;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['client', 'status'])->latest()->paginate(10);
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $clients = User::where('role', 'client')->get();
        $allStages = ProjectStage::orderBy('position')->get();
        $types = ProjectType::all();
        return view('admin.projects.create', compact('clients', 'allStages', 'types'));
    }

    public function store(Request $request, TelegramService $telegram)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
            'status_id' => 'nullable|exists:project_stages,id',
            'type_id' => 'nullable|exists:project_types,id',
            'rate' => 'nullable|string',
            'buffer_hours' => 'nullable|integer|min:1|max:168'
        ]);

        $project = Project::create($request->only([
            'name',
            'description',
            'client_id',
            'status_id',
            'type_id',
            'rate',
            'buffer_hours'
        ]));

        // Стандартні етапи
        $defaultStages = ProjectStage::orderBy('position')->get();

        foreach ($defaultStages as $stage) {
            ProjectStageItem::create([
                'project_id' => $project->id,
                'stage_id'   => $stage->id,
                'position'   => $stage->position,
                'expected_end_date' => NULL,
                'custom' => 0,
            ]);
        }

        // Telegram
        $threadId = $telegram->createThread($project->name);

        if ($threadId) {
            $project->update(['telegram_thread_id' => $threadId]);

            $telegram->sendMessage(
                "🆕 <b>Новий проєкт створено:</b>\n<b>Назва:</b> {$project->name}\n<b>ID:</b> {$project->id}",
                $threadId
            );
        }

        return redirect()->route('admin.projects.index')
            ->with('success', 'Проєкт створено успішно');
    }


    public function edit(Project $project)
    {
        $clients = User::where('role', 'client')->get();

        $project->load(['stageItems.stage']);
        $project->load('messages.user');

        $waitingActive = ProjectStageWaitingLog::where('project_id', $project->id)
            ->whereIn('status', ['running', 'pending', 'rejected', 'approved'])
            ->latest()
            ->first();

        $waitingHistory = ProjectStageWaitingLog::where('project_id', $project->id)
            ->with('messages')
            ->orderByDesc('id')
            ->get();

        $types = ProjectType::all();
        $allStages = ProjectStage::orderBy('position')->get();

        return view('admin.projects.edit', compact(
            'project',
            'clients',
            'types',
            'allStages',
            'waitingActive',
            'waitingHistory'
        ));
    }


    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'nullable|exists:users,id',
            'status_id' => 'nullable|integer',
            'type_id' => 'nullable|exists:project_types,id',
            'rate' => 'nullable|string',
            'buffer_hours' => 'required|integer|min:1|max:168'
        ]);

        // Видалення кастомних
        if ($request->has('delete_stage_ids')) {
            foreach ($request->delete_stage_ids as $id) {
                if ($id) {
                    $item = ProjectStageItem::find($id);
                    if ($item && $item->custom == 1) {
                        $item->delete();
                    }
                }
            }
        }

        // Оновлення існуючих етапів
        if ($request->has('stage')) {
            foreach ($request->stage as $itemId => $data) {
                $item = ProjectStageItem::find($itemId);
                if (!$item) continue;

                $start = $data['start_date'] ?: null;
                $end   = $data['end_date'] ?: null;

                $expected = null;
                if ($start) {
                    $stage = ProjectStage::find($item->stage_id);
                    if ($stage) {
                        $expected = \Carbon\Carbon::parse($start)
                            ->addDays($stage->avg_duration_days);
                    }
                }

                $item->update([
                    'start_date'        => $start,
                    'end_date'          => $end,
                    'expected_end_date' => $expected,
                ]);
            }
        }

        // Додавання кастомних етапів
        if ($request->has('new_stage')) {
            foreach ($request->new_stage as $data) {
                if (!empty($data['stage_id'])) {

                    $stage = ProjectStage::find($data['stage_id']);

                    ProjectStageItem::create([
                        'project_id' => $project->id,
                        'stage_id'   => $data['stage_id'],
                        'start_date' => $data['start_date'] ?? null,
                        'end_date'   => $data['end_date'] ?? null,
                        'position'   => $stage->position,
                        'expected_end_date' => $data['start_date']
                            ? \Carbon\Carbon::parse($data['start_date'])
                                ->addDays($stage->avg_duration_days)
                            : null,
                        'custom' => 1,
                    ]);
                }
            }
        }

        // 🔵 ОНОВЛЕННЯ ПРОЄКТУ (включно з buffer_hours)
        $project->update($validated);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Проєкт оновлено');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Проєкт видалено');
    }

    public function sendMessage(Request $request, Project $project)
    {
        $data = $request->validate(['message' => 'required|string']);

        $message = $project->messages()->create([
            'user_id' => auth()->id(),
            'message' => $data['message'],
            'from_client' => Auth::user()->role === 'client',
        ]);

        app(TelegramService::class)
            ->sendProjectMessage($message->message, $project->telegram_thread_id, $message->from_client);

        return back();
    }

    public function addStage(Request $request, Project $project)
    {
        $request->validate([
            'stage_id' => 'required|exists:project_stages,id',
        ]);

        $stage = ProjectStage::find($request->stage_id);

        $position = ProjectStageItem::where('project_id', $project->id)->max('position') + 1;

        ProjectStageItem::create([
            'project_id' => $project->id,
            'stage_id'   => $stage->id,
            'position'   => $position,
            'expected_end_date' => now()->addDays($stage->avg_duration_days),
            'custom' => 1,
        ]);

        return back()->with('success', 'Етап додано');
    }
}
