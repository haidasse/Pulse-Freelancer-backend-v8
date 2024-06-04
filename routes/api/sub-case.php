<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('sub-cases', Api\SubCaseController::class)->middleware('auth:api');