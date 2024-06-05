<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use App\Models\RolePermission;
use Symfony\Component\HttpFoundation\Request;

class RolePermissionController extends Controller
{
  

    public function assign($id,Request $request){
       $user=User::find($id);
      
       $name=$request->name;
       $role=Role::where(['name'=>$name]);
      
    }
}
