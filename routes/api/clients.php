<?php

use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\ClientController;

Route::apiResource('clients', ClientController::class)->middleware('auth:api');