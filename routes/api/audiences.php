<?php


use Illuminate\Support\Facades\Route;

Route::apiResource('audiences', AudienceController::class)->middleware('auth:api');