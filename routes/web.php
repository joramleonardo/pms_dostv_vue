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

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/members', [App\Http\Controllers\MembersController::class, 'index'])->name('members');
    Route::get('/project', [App\Http\Controllers\ProjectViewController::class, 'index'])->name('project-view');


    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index'); // Show all projects in a table
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create'); // Show create project form
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store'); // Store new project
    Route::get('/projects/{id}/edit', [ProjectController::class, 'edit'])->name('projects.edit'); // Show edit project form
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy'); // Delete project
    Route::put('/projects/{id}', [ProjectController::class, 'update'])->name('projects.update'); // Update project
    Route::get('/projects/recent', [ProjectController::class, 'getRecentProjects'])->name('projects.recent');

    // Route::get('/projects/title', [ViewSingleProjectController::class, 'index'])->name('viewsingleproject.index');
    Route::get('/projects/title/overview', [OverviewController::class, 'index'])->name('overview.index');
    Route::get('/projects/title/list', [ListController::class, 'index'])->name('list.index');
    Route::get('/projects/title/timeline', [TimelineController::class, 'index'])->name('timeline.index');
    Route::get('/projects/title/kanban', [KanbanController::class, 'index'])->name('kanban.index');
    Route::get('/projects/title/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/projects/title/attachments', [AttachmentsController::class, 'index'])->name('attachments.index');


    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');







    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::put('/users/{id}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    Route::put('/users/{id}/activate', [UserController::class, 'activate'])->name('users.activate');




});

require __DIR__.'/auth.php';
