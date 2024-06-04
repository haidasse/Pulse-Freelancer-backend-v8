<?php

namespace App\Exceptions;

use Exception;

class ModelNotValidException extends Exception
{
    private $tada;

    public function render()
    {
        return response($this->data, 400);
    }

    public function withData($data)
    {
        $this->data = $data;

        return $this;
    }
}
