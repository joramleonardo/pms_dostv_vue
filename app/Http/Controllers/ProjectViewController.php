<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectViewController extends Controller
{
    public function index(){
        return Inertia::render("Project/View");
    }
}
