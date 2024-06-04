<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

    /**
 * @OA\Schema(
 *     title="EventLog",
    * @OA\Property(property="action", type="string", example="created"),
     * @OA\Property(property="attributes", type="json", example="[]"),
     * @OA\Property(property="changes", type="json", example="[]"),
     * @OA\Property(property="original", type="json", example="[]"),
     * @OA\Property(property="object_type", type="string", example="customers"),
     * @OA\Property(property="object_id", type="integer", example="1"),
     * @OA\Property(property="causer_type", type="string", example="user"),
     * @OA\Property(property="causer_id", type="integer", example="1"),
     * @OA\Property(property="request_method", type="string", example="POST"),
     * @OA\Property(property="request_path", type="string", example="/api/customers"),
     * @OA\Property(property="created_at", type="datetime"),
 *     @OA\Xml(
 *         name="EventLog"
 *     )
 * )
 */

class EventLog extends Model
{
    use HasFactory;

    public $timestamps = false ;

    protected $fillable = [
        'action',
        'attributes',
        'changes',
        'object_id',
        'object_type',
        'original',
        'request_method',
        'request_path',
        'causer_type',
        'causer_id',
        'created_at',
    ];

    protected $casts = [
        "attributes" => "json",
        "original" => "json",
        "changes" => "json",
    ] ;

    public function rules()
    {
        return [
            'action' => 'required|string|max:2045',
            'object_id' => 'required|integer',
            'object_type' => 'required|string|max:150',
            'original' => 'json',
            'attributes' => 'json',
            'changes' => 'json',
            'request_method' => 'required|string|max:150',
            'request_path' => 'required|string|max:150',
            'causer_type' => 'nullable|string|max:45',
            'causer_id' => 'nullable|integer',
        ] ;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function log($attributes)
    {
        if (app('env') == "testing") {
            return false ;
        }

        $event = new EventLog() ;

        $default = [
            "request_method" => request()->getMethod(),
            "request_path" => request()->path(),
            "created_at" => Carbon::now() ,
        ] ;

        $event->fill($attributes) ;
        $event->fill($default) ;

        if (Auth::user()) {
            $causer = [
                "causer_type" =>"user" ,
                "causer_id" => Auth::user()->id ,
            ] ;
            $event->fill($causer) ;
        }
        /*
        if (Auth::customer()) {
            $causer = [
                "causer_type" =>"customer" ,
                "causer_id" => Auth::customer()->id ,
            ] ;
            $event->fill($causer) ;
        }*/
        $event->save() ;
    }
}
