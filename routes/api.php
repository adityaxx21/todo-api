<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoListController;
use App\Http\Controllers\ChartController;

Route::get('todos/export', [TodoListController::class, 'export']);
Route::apiResource('todos', TodoListController::class);
Route::apiResource('chart', ChartController::class);