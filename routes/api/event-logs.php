<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventLogController;

Route::get('event-logs', [EventLogController::class, 'index'])->name("event-logs.index") ;
Route::get('event-logs/{event}', [EventLogController::class, 'show'])->name("event-logs.show") ;