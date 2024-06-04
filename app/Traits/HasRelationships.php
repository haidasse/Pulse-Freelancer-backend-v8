<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait HasRelationships
{
    /**
     * Scope a query to sort results.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeRelationships(Builder $query, Request $request)
    {
        if ($request->has("with") && !empty($request->with)) {
            $relationships = explode(",", $request->with);
            $relationships = array_map("trim", $relationships);

            foreach ($relationships as $key => $value) {
                if (str_contains($value, ".")) {
                    continue;
                }

                if (!method_exists(__CLASS__, $value)) {
                    unset($relationships[$key]);
                }
            }

            // Include attachments relationship for sub_cases
            if (in_array('sub_cases', $relationships)) {
                $relationships[] = 'sub_cases.attachments';
            }

            return $query->with($relationships);
        }
    }
}
