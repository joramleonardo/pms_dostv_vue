<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Task;
use App\Models\User;
use App\Models\Project; // Import Project model

class ListController extends Controller
{
    public function index($id): Response {
        // Fetch the project by ID
        $project = Project::findOrFail($id);

        // Fetch tasks associated with this project
        $tasks = Task::with('assignees')->where('project_id', $id)->get();

        return Inertia::render("Projects/Sections/List", [
            'project' => $project, // Pass project data
            'tasks' => $tasks // Pass tasks
        ]);
    }
}
