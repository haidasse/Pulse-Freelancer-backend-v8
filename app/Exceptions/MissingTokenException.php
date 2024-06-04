<?php

namespace App\Exceptions;

use Exception;

class MissingTokenException extends Exception
{
    public function render()
    {
        return response('Missing token', 203);
    }
}
