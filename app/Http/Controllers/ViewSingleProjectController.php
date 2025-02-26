<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class ViewSingleProjectController extends Controller
{
    public function index(){
        return Inertia::render("Projects/ViewSingleProject");
    }


}
