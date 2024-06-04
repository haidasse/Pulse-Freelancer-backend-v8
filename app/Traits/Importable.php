<?php

namespace App\Traits;

use App\Exceptions\ModelNotValidException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

trait Importable
{
    public function import(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'file' => [
                'required',
                'file',
                            ]
        ]);

        if ($valid->fails()) {
            throw (new ModelNotValidException())->withData($valid->errors());
        }

        $importable = str_replace("Models", "Imports", get_class(new $this->model)) . "Import";

        $data = $request->all();
        unset($data["file"]);

        foreach ($data as $key => $value) {
            $data[$key] = Str::slug($value, "_");
        }

        try {
            $before = $this->model::all()->count();
            (new $importable($data))->import($request->file('file'));
            $after = $this->model::all()->count();
            return ["res"=>$after - $before. " elements imported"];
            // return response($after - $before. " elements imported");
        } catch (ValidationException $e) {
            throw (new ModelNotValidException())->withData($e->failures());
        }
    }

    private function importFormats()
    {
        return [
            "csv",
            "xls",
            "xlsx",
        ] ;
    }

    public function parse(Request $request)
    {
        $handle = fopen($request->file('file'),'r');
        $row = explode(";",fgets($handle));
        $row[array_key_last($row)] = str_replace(["\n","\r"],'',$row[array_key_last($row)]);
        fclose($handle);
        return $row;
    }
}
