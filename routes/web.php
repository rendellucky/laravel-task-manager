<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReorderTasksController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/tasks');

Route::patch('/tasks/reorder', ReorderTasksController::class)->name('tasks.reorder');
Route::resource('tasks', TaskController::class)->except('create', 'show');

Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
