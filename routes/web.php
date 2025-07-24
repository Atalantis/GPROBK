<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\AiAssistantController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CustomFieldDefinitionController;
use App\Http\Controllers\NotificationSettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ProjectController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notification Settings
    Route::get('/notification-settings', [NotificationSettingsController::class, 'index'])->name('notifications.settings');
    Route::post('/notification-settings/global', [NotificationSettingsController::class, 'toggleGlobal'])->name('notifications.global.toggle');
    Route::post('/notification-settings/mute', [NotificationSettingsController::class, 'toggleMute'])->name('notifications.mute.toggle');

    // AI Assistant
    Route::post('/ai/assist', [AiAssistantController::class, 'assist'])->name('ai.assist');

    // Admin / Professor specific routes
    Route::middleware('can:access-admin-features')->group(function () {
        Route::resource('custom-fields', CustomFieldDefinitionController::class)->except(['show']);
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

    // Attachments
    Route::post('projects/{project}/attachments', [AttachmentController::class, 'store'])->name('attachments.store');
    Route::delete('attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');

    // Comments
    Route::post('projects/{project}/comments', [CommentController::class, 'store'])->name('comments.store');

    // Tasks
    Route::post('projects/{project}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('projects/{project}/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::resource('tasks', TaskController::class)->except(['index', 'create', 'store']);
});

require __DIR__.'/auth.php';
