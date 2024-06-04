<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Sortable
{
    /**
     * Scope a query to sort results.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeSort(Builder $query, Request $request)
    {
        $sortable = $this->fillable ;

        if (property_exists(__CLASS__, 'sortable') && !empty($this->sortable)) {
            $sortable = array_merge($sortable, $this->sortable) ;
        }

        $sortable = array_merge($sortable, [
            'id',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by'
        ]) ;

        if ($request->has("sort") && !empty($request->sort)) {
            $sort = explode(",", $request->sort);
            $sort = array_map("trim", $sort) ;

            foreach ($sort as $value) {
                $direction = substr($value, 0, 1) == "-" ? "DESC" : "ASC";
                $value = $direction == "DESC" ? substr($value, 1) : $value ;

                if (!in_array($value, $sortable)) {
                    continue ;
                }

                $query->orderBy($value, $direction);
            }
            return $query ;
        }
    }
}
