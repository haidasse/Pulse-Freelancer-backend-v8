<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\RolePermission;
use App\Traits\HasCrudController;
use Validator;

class RoleController extends Controller
{
    use HasCrudController;

    protected $model = Role::class;

}
