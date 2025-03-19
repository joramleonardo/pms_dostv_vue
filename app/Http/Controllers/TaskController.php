<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Task;
use App\Models\User;
use App\Models\Subtask;


class TaskController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            'task_description' => 'nullable|string',
            'assignee_ids' => 'required|array',
            'assignee_ids.*' => 'exists:users,id',
            'task_status' => 'required|in:new,in_progress,completed',
            'task_due_date' => 'required|date',
            'task_category' => 'required|in:pre-production,production,post-production',
            'task_sub_category' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',

        ]);

        $task = Task::create($validated);

        $task->assignees()->attach($validated['assignee_ids']);

        return redirect()->route('list.index', ['id' => $validated['project_id']])
        ->with('success', 'Task created successfully!');

    }

    // DELETE Task
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('list.index')->with('success', 'Task deleted successfully!');
    }

    public function getUsers()
    {
        $allowedRoles = ['Tech Support', 'Assoc Producer', 'Editing Supervisor', 'Admin Staff', 'Marketing Staff'];

        $users = User::where('is_active', 1)
                    ->whereIn('role', $allowedRoles)
                    ->get();

        return response()->json($users);
    }

    public function getSubtasks($taskId)
    {
        try {
            // Check if the task exists
            $task = Task::findOrFail($taskId);

            // Fetch related subtasks
            $subtasks = Subtask::where('task_id', $taskId)->get();

            return response()->json($subtasks);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getSubtaskCounts()
    {
        try {
            $tasks = Task::withCount('subtasks')
                ->withCount(['subtasks as completed_subtasks' => function ($query) {
                    $query->where('status', 'completed');
                }])->get();

            $counts = [];

            foreach ($tasks as $task) {
                $counts[$task->id] = [
                    'total' => $task->subtasks_count ?? 0,
                    'completed' => $task->completed_subtasks ?? 0
                ];
            }

            return response()->json($counts);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }





}
