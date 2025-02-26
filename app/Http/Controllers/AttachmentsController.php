<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AttachmentsController extends Controller
{
    public function index(){
        return Inertia::render("Projects/Sections/Attachments");
    }
}
