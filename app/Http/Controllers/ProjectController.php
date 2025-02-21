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
    public function index(Request $request): Response
    {
        $search = $request->query('search');

        $projects = Project::when($search, function ($query, $search) {
                return $query->where('project_name', 'LIKE', "%{$search}%")
                            ->orWhere('coverage_segment', 'LIKE', "%{$search}%")
                            ->orWhere('description', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(10) // Ensure pagination is used
            ->withQueryString(); // Keep search query when paginating

        return Inertia::render('Projects/Index', [
            'projects' => $projects
        ]);
    }

    // New function to get the latest 5 projects for the sidebar
    public function getRecentProjects()
    {
        $recentProjects = Project::latest()->take(5)->get();
        return response()->json($recentProjects);
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
