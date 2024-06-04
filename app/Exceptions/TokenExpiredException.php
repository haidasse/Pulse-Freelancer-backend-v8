<?php

namespace App\Exceptions;

use Exception;

class TokenExpiredException extends Exception
{
    public function render()
    {
        return response($this->message, 203);
    }
}
