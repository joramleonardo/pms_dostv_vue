<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Task;
use App\Models\User;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            'task_description' => 'nullable|string',
            'assignee_id' => 'required|exists:users,id',
            'task_status' => 'required|in:new,in_progress,completed',
            'task_due_date' => 'required|date',
            'task_category' => 'required|in:pre-production,production,post-production',
            'task_sub_category' => 'required|string|max:255',
        ]);

        Task::create($validated);

        return redirect()->back()->with('success', 'Task created successfully!');
    }

    public function getUsers()
    {
        return response()->json(User::where('is_active', 1)->get());
    }
}
