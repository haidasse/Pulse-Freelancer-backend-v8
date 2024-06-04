<?php

namespace App\Traits;

use App\Exceptions\TokenExpiredException;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait IsTokenExpired
{
    public function isTokenExpired()
    {
        if (Auth::user()) {
            if (Auth::user()->token()->expires_at< Carbon::now()) {
                throw new TokenExpiredException('Token has expired on '. Auth::user()->token()->expires_at->toDateTimeString() .'. The current time is '. Carbon::now()->toDateTimeString() .'.');
            }
        }
    }
}
