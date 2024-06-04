<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Validator;

trait HasDatatable
{
    public function datatable()
    {
        $datatable = str_replace("Models", "Datatables", $this->model)."Datatable" ;

        if (class_exists($datatable) && method_exists($datatable, "config")) {
            $datatable = new $datatable ;
            return $datatable->options();
        }

        throw new Exception("Class ".$datatable."::config not found", 404) ;
    }
}
