<?php

namespace App\Models;

use App\Traits\HasCrudModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Sortable;
use App\Traits\Columns;

class Role extends Model
{
    use HasFactory, HasCrudModel;
    use Sortable, Columns;

    protected $fillable = [
        'name',
    ];

    protected $sortable = [
        'id',
    ];

    public function rules()
    {
        return [
            'name' => 'required|string|max:150',
        ] ;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissions()
    {
        return $this->hasMany(RolePermission::class);
    }
}
