<?php

namespace App\Exceptions;

use Exception;

class InvalidTokenException extends Exception
{
    public function render()
    {
        return response('Invalid token', 203);
    }
}