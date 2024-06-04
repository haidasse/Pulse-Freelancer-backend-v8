<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('custom-cases', Api\CustomCaseController::class)->middleware('auth:api');