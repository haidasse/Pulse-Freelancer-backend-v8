<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('clients', Api\ClientController::class)->middleware('auth:api');