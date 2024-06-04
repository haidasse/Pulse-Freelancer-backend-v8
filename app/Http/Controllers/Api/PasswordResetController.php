<?php

namespace App\Http\Controllers\Api;

use App\Models\PasswordReset;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    protected $model = PasswordReset::class;
}
