<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait HasCrudController
{
    public function index()
    {   
        // $table = app($this->model)->getTable() ;
        //$permission = $table.'.index' ;

        //$this->authorize($permission, Auth::user());

        $query = $this->model::sort(request())
                        ->columns(request())
                        ->relationships(request())
                        ->filterable(request()) ;

        if (request()->has("page")) {
            $per_page = request()->per_page ? request()->per_page : 50 ;
            return $query->paginate($per_page);
        }
        
        return $query->get();
    }
    
    public function show($id)
    {
        // $table = app($this->model)->getTable() ;
        // $permission = $table.'.show' ;

        // $this->authorize($permission, Auth::user());

        return $this->model::columns(request())
            ->relationships(request())
            ->findOrFail($id);
    }

    public function store(Request $request)
    {
        // $table = app($this->model)->getTable() ;
        // $permission = $table.'.create' ;

        //$this->authorize($permission, Auth::user());

        $model = new $this->model();
        $model->fill($request->all());
        $model->save();

        return $model;
    }

    public function update(Request $request, $id)
    {
        // $table = app($this->model)->getTable() ;
        // $permission = $table.'.update' ;

        // $this->authorize($permission, Auth::user());

        $model = $this->show($id);
        $model->fill($request->all());
        $model->save();

        return $model;
    }

    public function destroy($id)
    {
        // $table = app($this->model)->getTable() ;
        // $permission = $table.'.delete' ;

        // $this->authorize($permission, Auth::user());

        $model = $this->show($id);
        $model->delete();

        return $model;
    }
}
