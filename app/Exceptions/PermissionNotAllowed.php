<?php

namespace App\Exceptions;

use Exception;

class PermissionNotAllowed extends Exception
{
    public function render()
    {
        return response("PermissionNotAllowed, permission : ". $this->message, 203);
    }
}