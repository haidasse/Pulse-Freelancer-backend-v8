<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('judgements', Api\JudgementController::class)->middleware('auth:api');