<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

class SubtaskController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assignee_id' => 'nullable|exists:users,id',
        ]);

        // $subtask = Subtask::create($request->all());

        $subtask = Subtask::create([
            'task_id' => $request->task_id,
            'name' => $request->name,
            'description' => $request->description,
            'assignee_id' => $request->assignee_id,
            'status' => 'pending', // Automatically set to pending
        ]);

        return redirect()->back()->with('success', 'Subtask created successfully!');

    }

    public function updateStatus(Request $request, Subtask $subtask)
    {
        // Validate the status
        $request->validate([
            'status' => 'required|string|in:pending,in_progress,completed'
        ]);

        // Update the status
        $subtask->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Subtask status updated successfully!');
    }


    public function destroy(Subtask $subtask)
    {
        $subtask->delete();
        return response()->json(['message' => 'Subtask deleted successfully'], 200);
    }
}
