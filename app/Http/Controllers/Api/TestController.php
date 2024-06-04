<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Currency;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use App\Exceptions\ExceptionMultipleNine;

class TestController extends Controller
{
    public function fillables($table)
    {
        $data = DB::table('information_schema.columns')->select(
            'column_name',
            'data_type',
            'is_nullable',
            'character_maximum_length',
            'NUMERIC_PRECISION',
            'NUMERIC_SCALE'
        )->where(
            'table_schema',
            '=',
            DB::connection()->getDatabaseName()
        )->where(
            'table_name',
            '=',
            $table,
        )->get();

        //Fillable

        foreach ($data as $item) {
            $fillable[] = $item->COLUMN_NAME;
        }

        $fillable = array_values(array_diff(
            $fillable,
            [
                'id',
                'created_by',
                'updated_by',
                'created_at',
                'updated_at',
                'deleted_at',
            ]
        ));

        //Rules

        foreach ($data as $item) {
            $rules[$item->COLUMN_NAME] = '';
        }

        foreach ($data as $item) {
            switch ($item->IS_NULLABLE) {
                case 'YES':
                    $rules[$item->COLUMN_NAME] = 'nullable';
                    break;
                case 'NO':
                    $rules[$item->COLUMN_NAME] = 'required';
                    break;
            }

            $rules[$item->COLUMN_NAME] .= '|';

            switch ($item->DATA_TYPE) {
                case 'tinyint':
                    $rules[$item->COLUMN_NAME] .= 'boolean';
                    break;
                case 'timestamp':
                    $rules[$item->COLUMN_NAME] .= 'date';
                    break;
                case 'datetime':
                    $rules[$item->COLUMN_NAME] .= 'date';
                    break;
                case 'date':
                    $rules[$item->COLUMN_NAME] .= 'date';
                    break;
                case 'int': //10   todo : specify rule of int
                    $rules[$item->COLUMN_NAME] .= 'numeric|integer';
                    break;
                case 'bigint': //20   todo : specify rule of bigint
                    $rules[$item->COLUMN_NAME] .= 'numeric|integer';
                    break;
                case 'decimal': //todo : specify rule of decimal
                    $rules[$item->COLUMN_NAME] .= 'numeric';
                    break;
                case 'json':
                    $rules[$item->COLUMN_NAME] .= 'json';
                    break;
                case 'varchar' || 'char' || 'text' || 'longtext':
                    $rules[$item->COLUMN_NAME] .= 'string|size:' . $item->CHARACTER_MAXIMUM_LENGTH;
                    break;
            }
        }

        unset(
            $rules['id'],
            $rules['created_by'],
            $rules['updated_by'],
            $rules['created_at'],
            $rules['updated_at'],
            $rules['deleted_at'],
        );

        //Guarded

        foreach ($data as $item) {
            $guarded[] = $item->COLUMN_NAME;
        }

        $guarded = array_filter($guarded, function ($var) {
            return in_array($var, [
                'id',
                'created_by',
                'updated_by',
                'created_at',
                'updated_at',
                'deleted_at'
            ]);
        });

        //Affichage

        echo count($fillable) . "<br>";
        echo 'protected $fillable = [';
        foreach ($fillable as $item) {
            echo "<br>'$item',";
        }
        echo "<br>];<br><br>";

        echo 'protected $rules = [';
        foreach ($rules as $key => $item) {
            echo "<br>'$key' => '$item',";
        }
        echo "<br>];<br><br>";

        echo count($guarded)."<br>";
        echo 'protected $guarded = [';
        foreach ($guarded as $item) {
            echo "<br>'$item',";
        }
        echo "<br>];";
    }
    public function validation(Request $request)
    {
        $model = new Currency();
        $model->fill($request->all());
        $model->save();
        return $model;
    }
    public function export($table)
    {
        $data = DB::table('information_schema.columns')->select(
            'column_name',
            'data_type',
            'is_nullable',
            'character_maximum_length',
            'NUMERIC_PRECISION',
            'NUMERIC_SCALE'
        )->where(
            'table_schema',
            '=',
            DB::connection()->getDatabaseName()
        )->where(
            'table_name',
            '=',
            $table,
        )->get();

        foreach ($data as $item) {
            $tab[] = $item->COLUMN_NAME;
        }

        // dd(Schema::getColumnListing((new Document())->getTable()));

        // echo "[<br>";
        // foreach ($tab as $value) {
        //     echo "\$example->".$value.",<br>";
        // }
        // echo "]";

        // echo "<br>----------------------<br>";

        // echo "[<br>";
        // foreach ($tab as $value) {
        //     echo "\"" . $value . "\",<br>";
        // }
        // echo "]";
        // echo "<br>----------------------<br>";
        foreach ($tab as $value) {
            echo "\"".$value."\" => \"".ucfirst(strtolower(str_replace("_", " ", $value)))."\",<br>";
        }
        echo "<br>----------------------<br>";
        foreach ($tab as $value) {
            echo "\"".$value."\" => \$row->".$value.",<br>";
        }
    }
    private function square($number)
    {
        return $number * $number ;
    }
    private function square_many()
    {
        //lever une exception
        $results = [] ;
        for ($i = 0; $i< 10; $i++) {
            $rand = rand(10, 99) ;
            if ($rand % 10 == 0) {
                throw new Exception("number ".$rand." is a multiple of 10");
            }
            if ($rand % 7 == 0) {
                throw new Exception("number ".$rand." is a multiple of 7");
            }
            $results[$rand] = $this->square($rand) ;
        }

        return  $results ;
    }
    public function exception()
    {
        $results = $this->square_many() ;

        return $results ;
    }
    public function import($table)
    {
        $data = DB::table('information_schema.columns')->select(
            'column_name',
            'data_type',
            'is_nullable',
            'character_maximum_length',
            'NUMERIC_PRECISION',
            'NUMERIC_SCALE'
        )->where(
            'table_schema',
            '=',
            DB::connection()->getDatabaseName()
        )->where(
            'table_name',
            '=',
            $table,
        )->get();

        foreach ($data as $item) {
            $tab[] = $item->COLUMN_NAME;
        }

        foreach ($tab as $value) {
            echo "\"".$value."\" => \$row[\"".ucfirst(str_replace("_", " ", $value))."\"],<br>";
        }
    }
    public function datatable($table)
    {
        dump($table);

        $res = DB::select("SELECT COLUMN_NAME, COLUMN_TYPE
            from INFORMATION_SCHEMA.COLUMNS
            where table_schema = 'invoicefit'
            and table_name = ?", [$table]) ;

        foreach ($res as $value) {
            $filter = $value->COLUMN_TYPE ;

            if (strstr($value->COLUMN_TYPE, "int") || strstr($value->COLUMN_TYPE, "decimal")) {
                $filter = "numeric" ;
            }
            if (strstr($value->COLUMN_TYPE, "varchar") || strstr($value->COLUMN_TYPE, "text")) {
                $filter = false ;
            }
            if (strstr($value->COLUMN_TYPE, "timestamp")) {
                $filter = "date" ;
            }

            if ($filter) {
                $filter = ', "filter" => "'.$filter.'"' ;
            }

            echo '["name" => "'.$value->COLUMN_NAME.'"'.$filter.'],<br/>' ;
        }

        echo "<br/><br/>" ;

        foreach ($res as $value) {
            $label = "datatable.".$table.".".$value->COLUMN_NAME ;
            echo '\''.$label.'\' : "'.$value->COLUMN_NAME.'",<br/>' ;
        }
    }
}
