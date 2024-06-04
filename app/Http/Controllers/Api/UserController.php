<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Traits\HasCrudController;
use App\Traits\HasDatatable;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use HasCrudController, HasDatatable;

    protected $model = User::class;

    public function store(Request $request)
    {
        $model = new $this->model();
        $data = $request->all();

        // Hash the password if it's present in the request
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $model->fill($data);
        $model->save();

        return $model;
    }
}
