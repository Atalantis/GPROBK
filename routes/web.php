<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\AiAssistantController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CustomFieldDefinitionController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\MyTasksController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationSettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ProjectController::class, 'index'])->name('dashboard');
    Route::get('/my-tasks', [MyTasksController::class, 'index'])->name('my-tasks.index');
    Route::get('/search', SearchController::class)->name('search');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notification Settings
    Route::get('/notification-settings', [NotificationSettingsController::class, 'index'])->name('notifications.settings');
    Route::post('/notification-settings/global', [NotificationSettingsController::class, 'toggleGlobal'])->name('notifications.global.toggle');
    Route::post('/notification-settings/mute', [NotificationSettingsController::class, 'toggleMute'])->name('notifications.mute.toggle');

    // Notifications Inbox
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readall');

    // AI Assistant
    Route::post('/ai/assist', [AiAssistantController::class, 'assist'])->name('ai.assist');

    // Admin / Professor specific routes
    Route::middleware('can:access-admin-features')->group(function () {
        Route::resource('custom-fields', CustomFieldDefinitionController::class)->except(['show']);
        Route::resource('categories', CategoryController::class)->except(['show']);
    });

    // Projects
    Route::get('projects/{project}/kanban', [ProjectController::class, 'kanban'])->name('projects.kanban');
    Route::get('projects/{project}/gantt', [ProjectController::class, 'gantt'])->name('projects.gantt');
    Route::get('projects/{project}/gantt-data', [ProjectController::class, 'ganttData'])->name('projects.gantt.data');
    Route::get('projects/{project}/calendar', [ProjectController::class, 'calendar'])->name('projects.calendar');
    Route::get('projects/{project}/calendar-data', [ProjectController::class, 'calendarData'])->name('projects.calendar.data');
    Route::get('projects/{project}/brief', [ProjectController::class, 'brief'])->name('projects.brief');
    Route::patch('projects/{project}/brief', [ProjectController::class, 'updateBrief'])->name('projects.brief.update');
    Route::resource('projects', ProjectController::class);

    // Milestones (nested under projects)
    Route::resource('projects.milestones', MilestoneController::class)->shallow()->except(['index', 'show']);

    // Links
    Route::post('/links', [LinkController::class, 'store'])->name('links.store');
    Route::delete('/links/{link}', [LinkController::class, 'destroy'])->name('links.destroy');

    // Attachments
    Route::post('projects/{project}/attachments', [AttachmentController::class, 'store'])->name('attachments.store');
    Route::delete('attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');

    // Comments
    Route::post('projects/{project}/comments', [CommentController::class, 'store'])->name('comments.store');

    // Tasks
    Route::get('/api/tasks/{task}', [TaskController::class, 'showApi'])->name('tasks.show.api');
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update.status');
    Route::post('projects/{project}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('projects/{project}/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::resource('tasks', TaskController::class)->except(['index', 'create', 'store']);
});

require __DIR__.'/auth.php';
