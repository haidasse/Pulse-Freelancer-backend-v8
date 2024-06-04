<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('roles', Api\RoleController::class)->middleware('auth:api');