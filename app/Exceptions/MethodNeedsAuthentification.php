<?php

namespace App\Exceptions;

use Exception;

class MethodNeedsAuthentification extends Exception
{
    public function render()
    {
        return response('Not authentified !', 401);
    }
}
