<?php

namespace App\Models;

use App\Traits\HasFillable;
use App\Traits\HasRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Sortable;
use App\Traits\Columns;

class PasswordReset extends Model
{
    use HasFactory, Sortable, Columns;

    protected $fillable = [
        'email',
        'token',
    ];

    protected $sortable = [
        'id',
    ];

    public function rules()
    {
        return [
            'email' => 'required|string|max:150|email',
            'token' => 'required|string|max:150',
        ];
    }
}
