<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('courts', Api\CourtController::class)->middleware('auth:api');