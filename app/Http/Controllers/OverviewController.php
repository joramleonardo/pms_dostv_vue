<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OverviewController extends Controller
{
    public function index($id){


        $project = Project::find($id);
        return Inertia::render("Projects/Sections/Overview", [
            'project' => $project
        ]);

    }



}
