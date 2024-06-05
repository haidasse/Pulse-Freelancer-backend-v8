<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoleController;

Route::apiResource('roles', RoleController::class)->middleware('auth:api');