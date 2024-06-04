<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Columns
{
    /**
     * Scope a query to sort results.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeColumns(Builder $query, Request $request)
    {
        if ($request->has("columns") && !empty($request->columns)) {
            $columns = explode(",", $request->columns);
            $columns = array_map("trim", $columns) ;

            return $query->select($columns);
        }
    }
}
