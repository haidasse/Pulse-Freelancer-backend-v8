<?php

namespace App\Models;

use App\Traits\HasCrudModel;
use App\Traits\HasRelationships;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

use App\Traits\Sortable;
use App\Traits\Columns;
use App\Traits\Filterable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;
    use Sortable,
        Columns,
        HasRelationships,
        Filterable,
        HasCrudModel;

    protected $guarded = [
        'id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $sortable = [
        "id",
    ];

    public function rules()
    {
        return [
            'email' => 'required|email',
            'first_name' => 'required|string|max:150',
            'last_name' => 'required|string|max:150',
            'password' => 'nullable|string|max:150',
            'role_id' => 'nullable|integer',
        ] ;
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getExtraAttributes()
    {
        return [
            "full_name" => $this->getFullNameAttribute() ,
            "initials" => substr($this->first_name, 0, 1).substr($this->last_name, 0, 1) ,
        ] ;
    }

    public function sub_cases()
    {
        return $this->hasMany(SubCase::class);
    }
}
