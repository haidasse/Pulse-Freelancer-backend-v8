<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttachmentController;


Route::get('/attachments/download/{name}', 'Api\AttachmentController@download')->name('attachments.download');

Route::middleware('auth:api')->group(function () {
    Route::post('/attachments', 'Api\AttachmentController@upload')->name('attachments.upload');
    Route::delete('/attachments/{name}', 'Api\AttachmentController@remove')->name('attachments.remove');
    Route::apiResource('attachments', AttachmentController::class)->only(['index', 'show', 'update']);
}) ;