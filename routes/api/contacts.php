<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('contacts', Api\ContactController::class)->middleware('auth:api');