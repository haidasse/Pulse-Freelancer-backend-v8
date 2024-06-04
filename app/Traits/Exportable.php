<?php

namespace App\Traits;

use Exception;

trait Exportable
{
    public function export()
    {
        $format = request()->has("format") ? strtoupper(request()->format) : "CSV";
        $format = key_exists($format, $this->exportFormats()) ? mb_convert_case($format, MB_CASE_TITLE) : "Csv";

        $extension = strtolower($format);
        $exportable = str_replace("Models", "Exports", $this->model)."Export" ;

        if (class_exists($exportable) && method_exists($exportable, "download")) {
            $export = new $exportable ;
            return $export->download(app($this->model)->getTable()."-".date("Ymdhis").".". $extension, $format);
        }

        throw new Exception("Class ".$exportable."::download not found", 404) ;
    }
    private function exportFormats()
    {
        return [
            "XLSX" => "Xlsx" ,
            "CSV" => "Csv" ,
            "XLS" => "Xls" ,
            "HTML" => "Html" ,
            "MPDF" => "Mpdf" ,
        ] ;
    }
}
