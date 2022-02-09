<?php

use App\Models\Plan;
use App\Models\Project;
use App\Events\TestEvent;
use App\Events\ProjectUpdated;
use App\Honeypot\Middleware\BlockSpam;
use App\Http\Livewire\Dashboard;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Livewire\ProjectsDashboard;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\GithubAuthController;
use App\Http\Controllers\ProjectTasksController;
use App\Http\Controllers\ProjectInvitesController;
use Illuminate\Http\Request;

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

  // $project = auth()->user()->projects()->first();

  // $subscription = $project->cancelCurrentSubscription();

  // $subscription = $project->subscribeTo(Plan::first(), 15);
  // $project->cancelCurrentSubscription();
  // dump($project->activeSubscription());
  // $projects = auth()->user()->authorizedProjects()->latest('updated_at')->get();
  // $project = Project::find(30);
  // Redis::flushall();
  // Cache::flush();
  // $project = Cache::get("projects.30");
  // $changes = $project->activities()->latest('updated_at')->skip(1)->first()->changes;
  // dump($changes);
  // dump(collect($changes['after'])->diff($changes['before']));
  // TestEvent::dispatch($projects);

  // return (new App\Notifications\SomeNotification($project))
  // ->toMail($project->members);
  // dump($project);
    return view('welcome');
});

// Route::get('/', function () {
//   // ProjectUpdated::broadcast(Project::factory()->create());
//   // TestEvent::broadcast();
//   return view('welcome');
// });


Route::group(['middleware' => 'auth'], function () {
    Route::resource('projects', ProjectsController::class);

    Route::post('/invites/{project}', [ProjectInvitesController::class, 'store'])->name('project.invite');
    Route::post('/projects/{project}/tasks', [ProjectTasksController::class, 'store'])->name('project.tasks');
    Route::patch('/projects/{project}/tasks/{task}', [ProjectTasksController::class, 'update'])->name('tasks.update');
});

Route::post('/', function (Request $request) {
    // dd($request);
})->middleware([BlockSpam::class])->name('welcome.post');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', Dashboard::class)->name('dashboard');


Route::get('login/github', [GithubAuthController::class, 'create']);
Route::get('login/github/callback', [GithubAuthController::class, 'store']);
