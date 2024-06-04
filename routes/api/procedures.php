<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('procedures', Api\ProcedureController::class)->middleware('auth:api');