<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Login and Registre and logout routes:
Route::post('/login', 'Auth\AuthController@login')->name('login');
Route::post('/register', 'Auth\AuthController@register')->name('register');
Route::get('/logout', 'Auth\AuthController@logout')->name('logout')->middleware('auth:api');


//forgot and reset password :
Route::post('/password/create', 'Auth\PasswordResetController@create')->name('password.created');
Route::get('/password/find/{token}', 'Auth\PasswordResetController@find')->name('password.find');
Route::post('/password/reset', 'Auth\PasswordResetController@reset')->name('password.reset');
Route::post('/password/update', 'Auth\PasswordResetController@update')->name('password.update')->middleware('auth:api');

//Email Verification
Route::get('/email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
Route::get('/email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');

//Google Signup
Route::get('auth/google', 'Auth\GoogleController@redirectToGoogle')->name('google.redirect');
Route::get('auth/google/callback', 'Auth\GoogleController@handleGoogleCallback')->name('google.callback');
