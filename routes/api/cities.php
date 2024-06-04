<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('cities', Api\CityController::class)->middleware('auth:api');