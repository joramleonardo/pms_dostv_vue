<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;



class ProjectController extends Controller
{
    public function index(Request $request)
    {

        return Inertia::render('Projects/Index');
    }

    public function getProjects(Request $request)
    {
        $search = $request->search;
        $perPage = $request->perpage ?? 10; // Default to 10 if not provided
        $page = $request->page ?? 1; // Default to page 1 if not provided

        $projects = Project::when($search, function ($query, $search) {
            return $query->where('project_name', 'LIKE', "%{$search}%")
                        ->orWhere('coverage_segment', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
        })
        ->latest()
        ->paginate($perPage); // Paginate results

        return response()->json($projects); // Return JSON with pagination data

    }

    public function getAssignedProjects(Request $request)
    {
        $user = auth()->user(); // Get the authenticated user

        // Ensure we only fetch projects where the user has assigned tasks
        $projects = Project::whereHas('tasks', function ($query) use ($user) {
            $query->whereHas('assignees', function ($subQuery) use ($user) {
                $subQuery->where('users.id', $user->id); // Match tasks where the user is assigned
            });
        })
        ->latest()
        ->paginate(10); // Paginate the results

        return response()->json($projects);
    }


    // public function getProjects(Request $request)
    // {
    //     $search = $request->search;
    //     $perPage = $request->perpage ?? 10;
    //     $page = $request->page ?? 1;
    //     $user = auth()->user(); // Get the authenticated user

    //     $projects = Project::when($search, function ($query, $search) {
    //             return $query->where('project_name', 'LIKE', "%{$search}%")
    //                         ->orWhere('coverage_segment', 'LIKE', "%{$search}%")
    //                         ->orWhere('description', 'LIKE', "%{$search}%");
    //         })
    //         ->when(in_array($user->role, ['Assoc Producer', 'Editing Supervisor']), function ($query) use ($user) {
    //             return $query->whereHas('tasks.assignees', function ($taskQuery) use ($user) {
    //                 $taskQuery->where('user_id', $user->id);
    //             });
    //         })
    //         ->latest()
    //         ->paginate($perPage);

    //     return response()->json($projects);
    // }



    public function view($id): Response
    {
        $project = Project::findOrFail($id);

        return Inertia::render("Projects/ViewSingleProject", [
            'project' => $project
        ]);
    }


    public function getRecentProjects()
    {
        $projects = Project::orderBy('created_at', 'desc')->take(5)->get();
        return response()->json($projects);
    }



    public function create(): Response
    {
        return Inertia::render('Projects/Create');
    }

    public function store(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'coverage_segment' => 'required|string',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'project_status' => 'required|string|in:pending,in-progress,completed', // ✅ Add validation
        ]);

        // Assign created_by using authenticated user's ID
        $validated['created_by'] = Auth::id();

        // Create project
        $project = Project::create($validated);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function edit($id): Response
    {
        $project = Project::findOrFail($id);
        return Inertia::render('Projects/Edit', [
            'project' => $project
        ]);
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $request->validate([
            'project_name' => 'required|string|max:255',
            'coverage_segment' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $project->update([
            'project_name' => $request->project_name,
            'coverage_segment' => $request->coverage_segment,
            'description' => $request->description
        ]);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy($id): RedirectResponse
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}
