<?php


use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\ViewSingleProjectController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AttachmentsController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SubtaskController;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    //PROFILE SETTINGS
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //DASHBOARD MANAGEMENT
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    //USERS MANAGEMENT
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::put('/users/{id}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    Route::put('/users/{id}/activate', [UserController::class, 'activate'])->name('users.activate');

    //PROJECT MANAGEMENT
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects-create', [ProjectController::class, 'create'])->name('projects.create'); // Show create project form
    Route::post('/projects-store', [ProjectController::class, 'store'])->name('projects.store'); // Store new project
    Route::get('/projects-edit/{id}', [ProjectController::class, 'edit'])->name('projects.edit'); // Show edit project form
    Route::delete('/projects-delete/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy'); // Delete project
    Route::put('/projects-update/{id}', [ProjectController::class, 'update'])->name('projects.update'); // Update project
    Route::get('/projects-recent', [ProjectController::class, 'getRecentProjects'])->name('projects.recent');
    Route::get('/get-projects', [ProjectController::class, 'getProjects'])->name('projects.get-projects');
    Route::get('/get-assigned-projects', [ProjectController::class, 'getAssignedProjects'])->name('projects.get-assigned-projects');



    //PROJECT SECTION MANAGEMENT
    Route::get('/projects-overview/{id}', [OverviewController::class, 'index'])->name('overview.index');
    Route::get('/projects-list/{id}', [ListController::class, 'index'])->name('list.index');
    Route::get('/projects-timeline/{id}', [TimelineController::class, 'index'])->name('timeline.index');
    Route::get('/projects-kanban/{id}', [KanbanController::class, 'index'])->name('kanban.index');
    Route::get('/projects-calendar/{id}', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/projects-attachments/{id}', [AttachmentsController::class, 'index'])->name('attachments.index');



    // TASK MANAGEMENT
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks-get-users', [TaskController::class, 'getUsers'])->name('tasks.get-users');
    Route::delete('/tasks-delete/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy'); // Add delete route
    Route::get('/tasks/{task}/subtasks', [TaskController::class, 'getSubtasks'])->name('tasks.subtasks');
    Route::get('/tasks/subtask-counts', [TaskController::class, 'getSubtaskCounts']);




    //SUBTASKS MANAGEMENT
    Route::post('/subtasks', [SubtaskController::class, 'store'])->name('subtasks.store');
    Route::delete('/subtasks/{subtask}', [SubtaskController::class, 'destroy'])->name('subtasks.destroy');
    Route::put('/subtasks/{subtask}/update-status', [SubtaskController::class, 'updateStatus'])->name('subtasks.update-status');



});

require __DIR__.'/auth.php';
