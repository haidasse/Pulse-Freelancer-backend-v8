<?php

namespace App\Traits;

use Exception;
use App\Models\User;
use App\Models\EventLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\ModelNotValidException;
use Illuminate\Database\Eloquent\SoftDeletes;

trait HasCrudModel
{
    use HasRelationships, SoftDeletes;
    use Filterable;

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = Auth::user() ? Auth::user()->id : null;
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::user() ? Auth::user()->id : null;
        });

        static::retrieved(function ($model) {

            $table = $model->getTable() ;

            if (method_exists($model, "getExtraAttributes")) {
                $model->setAttribute("extra", $model->getExtraAttributes()) ;
                return $model ;
            }
        });

        static::saving(function ($model) {
            self::validate($model);

            $table = $model->getTable() ;

            if (isset($model->extra)) {
                unset($model->attributes['extra']) ;
            }
        });

        static::created(function ($model) {
            self::log("create", $model) ;
        });

        static::updated(function ($model) {
            self::log("update", $model) ;
        });

        static::deleted(function ($model) {
            self::log("delete", $model) ;
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    private static function validate($model)
    {
        if (!method_exists($model, "rules")) {
            throw (new Exception(__CLASS__." doesnt have a rules method"));
        }

        $validator = Validator::make($model->attributes, $model->rules());

        if ($validator->fails()) {
            dd($validator->errors());
            throw (new ModelNotValidException())->withData($validator->errors());
        }
    }

    private static function log($action, $model)
    {
        $event = [
            "object_type" => $model->getTable() ,
            "object_id" => $model->id,
            "action" => $action,
            "attributes" => $model->attributes,
            "original" => $model->original,
            "changes" => $model->changes,
        ] ;
        EventLog::log($event) ;
    }
}
