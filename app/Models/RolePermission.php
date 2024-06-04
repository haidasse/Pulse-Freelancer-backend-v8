<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    public $timestamps = false ;

    protected $fillable = [
        'name',
        'role_id',
        'is_permitted',
    ];

    protected $sortable = [
        'id',
    ];

    public function rules()
    {
        return [
            'name' => 'required|string|max:150',
            'role_id' => 'required|integer',
            'is_permitted' => 'boolean|default:false'
        ] ;
    }

    public function role()
    {
        return $this->belongsTo(RolePermission::class);
    }
}
