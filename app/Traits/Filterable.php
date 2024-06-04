<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Filterable
{
    /**
    * Scope a query to sort results.
    *
    * @param \Illuminate\Database\Eloquent\Builder $query
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Database\Eloquent\Builder
    */

    public function scopeFilterable(Builder $query, Request $request)
    {
        $filters = $this->getFilters($request) ;
        $operators =  $this->operators();

        foreach ($filters as $value) {
            switch ($value['operator']) {
                case "between":
                    $query = $query->whereBetween($value['column'], [$value['from'], $value['to']]) ;
                break ;
                case "in":
                    $query = $query->whereIn($value['column'], $value['values']) ;
                break ;
                case "contains":
                    $query = $query->where($value['column'], "LIKE", "%".$value['value']."%") ;
                break ;
                case "starts":
                    $query = $query->where($value['column'], "LIKE", $value['value']."%") ;
                break ;
                case "ends":
                    $query = $query->where($value['column'], "LIKE", "%".$value['value']) ;
                break ;
                default:
                  $query = $query->where($value['column'], $operators[$value['operator']], $value['value']) ;
            }
        }

        return $query;
    }
    private function operators()
    {
        return [
            'like' => 'LIKE',
            '!like' => 'NOT LIKE',
            'in' => 'IN',
            'lt' => '<',
            'lte' => '<=',
            'gt' => '>',
            'gte' => '>=',
            'eq' => '=',
            '!eq' => '!=',
            'between' => 'BETWEEN',
            'starts' => 'starts',
            'ends' => 'ends',
            'contains' => 'contains',
        ] ;
    }
    private function getFilters(Request $request)
    {
        $filters = [] ;
        $filterable = $this->fillable ;
        $operators =  $this->operators();

        if (property_exists(__CLASS__, 'filterable') && !empty($this->filterable)) {
            $filterable = array_merge($filterable, $this->filterable) ;
        }

        $filterable = array_merge($filterable, [
            'id',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by'
        ]) ;

        foreach ($request->all() as $key=> $value) {
            // dd($request->all());
            if (!in_array($key, $filterable) || empty($value)) {
                continue ;
            }

            if (!str_contains($value, ':')) {
                $filters[] = [
                    "column" => $key,
                    "operator" => 'eq',
                    "value" => $value ,
                ] ;
                continue ;
            }

            $value = explode(':', $value);
            $value = array_map("trim", $value);
            $operator = $value[0] ;

            if (!in_array($operator, array_keys($operators))) {
                continue ;
            }

            if (empty($value[1])) {
                continue ;
            }

            switch ($operator) {
                case "between":

                    $value = explode(',', $value[1]);

                    $filters[] = [
                        "column" => $key,
                        "operator" => $operator,
                        "from" => $value[0] ,
                        "to" => $value[1] ,
                    ] ;

                break ;
                case "in":

                    $values = explode(',', $value[1]);

                    $filters[] = [
                        "column" => $key,
                        "operator" => $operator,
                        "values" => $values ,
                    ] ;

                break ;
                default:

                    $filters[] = [
                        "column" => $key,
                        "operator" => $operator,
                        "value" => $value[1] ,
                    ] ;
            }
        }
        return $filters ;
    }
}
