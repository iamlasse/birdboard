<?php

use App\Http\Controllers\GithubAuthController;
use App\Http\Controllers\ProjectInvitesController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ProjectTasksController;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

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
  return view('welcome');
});


Route::group(['middleware' => 'auth'], function () {
  Route::resource('projects', ProjectsController::class);
  
  Route::post('/invites/{project}', [ProjectInvitesController::class, 'store'])->name('project.invite');
  Route::post('/projects/{project}/tasks', [ProjectTasksController::class, 'store'])->name('project.tasks');
  Route::patch('/projects/{project}/tasks/{task}', [ProjectTasksController::class, 'update'])->name('tasks.update');
});


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
  return Inertia\Inertia::render('Dashboard');
})->name('dashboard');


Route::get('login/github', [GithubAuthController::class, 'create']);
Route::get('login/github/callback', [GithubAuthController::class, 'store']);